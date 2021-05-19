import pygame
from animation import Animation
from particle import ParticlesHandler

class Player:
    def __init__(self, start_pos, size, keys_dico, sprites, s):
        self.start_pos = start_pos
        self.keys_dico = keys_dico
        self.sprites = sprites
        self.s = s
        self.particles_handler = ParticlesHandler(self.s)
        self.width = size[0]
        self.height = size[1]
        self.speed = size[0] / 18 # speed of the player


        self.animation = Animation(len(sprites["player_sprites_alive"]), speed=0.2)

        self.reset()


    def reset(self):
        """reset all player variables"""
        self.pos = [self.start_pos[0], self.start_pos[1]]
        self.rect = pygame.Rect(self.start_pos[0], self.start_pos[1], self.width, self.height)
        self.score = 0
        self.color =(5,77,222) # the color of the player
        self.sprites_right = [pygame.transform.smoothscale(sprite, (self.rect.w, self.rect.h)) for sprite in self.sprites["player_sprites_alive"]]
        self.sprites_left =[pygame.transform.flip(sprite, True, False) for sprite in self.sprites_right]
        self.sprites_dead = {"right": pygame.transform.smoothscale(self.sprites["player_sprites_dead"], (self.rect.w, self.rect.h))}
        self.sprites_dead["left"] = pygame.transform.flip(self.sprites_dead["right"], True, False)
        self.direction = "right"
        self.is_dead = False
        self.has_make_dead_particles = False


    def draw_score(self):
        """draw the score of the player"""
        score_label = main_font.render(f"score : {self.score}", 1, (222,222,222))
        pos_x = self.rect.centerx - score_label.get_width()//2
        pos_y = self.rect.y - score_label.get_height()
        self.s.blit(score_label, (pos_x, pos_y))


    def draw(self):
        """draw the player on the screen"""
        #pygame.draw.rect(self.s, self.color, self.rect)

        self.animation.do()
        if self.is_dead:
            sprite = self.sprites_dead[self.direction]
            self.s.blit(sprite, (self.rect.x, self.rect.y))
        else:
            if self.direction == "right":
                sprite = self.sprites_right[self.animation.current_frame]
                self.s.blit(sprite, (self.rect.x, self.rect.y))
            else:
                sprite = self.sprites_left[self.animation.current_frame]
                self.s.blit(sprite, (self.rect.x, self.rect.y))


    def outside_screen_check(self):
        """replace the player in the screen if the player is outside the screen"""
        # right / left
        if self.rect.right > self.s.get_width():
            self.rect.right = self.s.get_width()
            self.pos[0] = self.rect.x
        elif self.rect.left < 0:
            self.rect.left = 0
            self.pos[0] = 0
        # up / down
        elif self.rect.bottom > self.s.get_height():
            self.rect.bottom = self.s.get_height()
            self.pos[1] = self.rect.y
        elif self.rect.top < 0:
            self.rect.top = 0
            self.pos[1] = 0


    def move(self, obstacles_list):
        """move the player"""
        keys_pressed = pygame.key.get_pressed()

         # left / right
        speed = 0
        if keys_pressed[self.keys_dico["left"]]:
            speed -= self.speed
            self.direction = "left"

        if keys_pressed[self.keys_dico["right"]]:
            speed += self.speed
            self.direction = "right"


        self.pos[0] += speed
        self.rect.x = self.pos[0] #change the player x rect  position to the player position

        # check for collision left and right with obstacles
        for obstacle in obstacles_list:
            if self.rect.colliderect(obstacle.rect):
                if speed > 0:
                    self.rect.right = obstacle.rect.left
                    self.pos[0] = self.rect.x
                elif speed < 0:
                    self.rect.left = obstacle.rect.right
                    self.pos[0] = self.rect.x

                if obstacle.label == "kill":
                    self.is_dead = True

        self.outside_screen_check()

        # up / down
        speed = 0
        if keys_pressed[self.keys_dico["up"]]:
            speed -= self.speed

        if keys_pressed[self.keys_dico["down"]]:
            speed += self.speed

        self.pos[1] += speed
        self.rect.y = self.pos[1] #change the player x rect  position to the player position

        # check for collision up and down with obstacles
        for obstacle in obstacles_list:
            if self.rect.colliderect(obstacle.rect):
                if speed > 0:
                    self.rect.bottom = obstacle.rect.top
                    self.pos[1] = self.rect.y
                elif speed < 0:
                    self.rect.top = obstacle.rect.bottom
                    self.pos[1] = self.rect.y

                if obstacle.label == "kill":
                    self.is_dead = True

        self.outside_screen_check()


    def dead_particles(self):
        if self.has_make_dead_particles == False:
            self.has_make_dead_particles = True
            self.particles_handler.add_particles("blood", pos=(self.rect.centerx, self.rect.centery), to_number=20)
        self.particles_handler.do()

    def do(self, obstacles_list):
        """make the player funcional"""
        if not self.is_dead:
            self.move(obstacles_list)
        else:
            self.dead_particles()
        self.draw()
        # self.draw_score()
