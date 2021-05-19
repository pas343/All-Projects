import pygame
import random

import player
import gui
from maze_patterns import all_levels
from animation import Animation
from particle import ParticlesHandler


class Key:
    """Create a key"""
    def __init__(self, x, y, size, key_sprites, s):
        self.size = size
        self.rect = pygame.Rect(x, y, size[0], size[1])
        self.sprites = [pygame.transform.smoothscale(sprite, self.size) for sprite in key_sprites]

        self.animation = Animation(len(self.sprites), speed=0.1, bouncing=True)

        self.particles_handler = ParticlesHandler(s)


    def draw(self, s):
        self.particles_handler.add_particles("key", pos=(self.rect.centerx, self.rect.centery), to_number=10)
        self.particles_handler.do()
        self.animation.do()
        s.blit(self.sprites[self.animation.current_frame], (self.rect.x, self.rect.y))


    def check_collide(self, player_rect):
        """check if the player collide with the key"""
        if player_rect.colliderect(self.rect):
            print("+ player collect key")
            return "to_remove"

    def do(self, s, player_rect):
        """make the key functional"""
        self.draw(s)
        if self.check_collide(player_rect) == "to_remove":
            return "to_remove"



class Gate:
    def __init__(self, x, y, size, open_sprite, close_sprite, s):
        self.size = size
        self.rect = pygame.Rect(x, y, size[0]*2, size[1]*2)
        self.gate_open_sprite = pygame.transform.scale(open_sprite, (self.size[0]*2, self.size[1]*2))
        self.gate_close_sprite = pygame.transform.scale(close_sprite, (self.size[0]*2, self.size[1]*2))
        m_pos =  pygame.mouse.get_pos()
        self.particles_handler = ParticlesHandler(s)
        self.particles_nb = 40
        self.has_made_particles = False




    def draw(self, s, keys_number):
        if keys_number != 0 :
            color = (11,66,11)
            s.blit(self.gate_close_sprite, (self.rect.x, self.rect.y))
        else:
            if not self.has_made_particles:
                self.has_made_particles = True
                for n in range(self.particles_nb):
                    pos = [random.randint(self.rect.left, self.rect.right), random.randint(self.rect.top, self.rect.bottom)]
                    particle_type = random.choice(("gate_purple", "gate_green"))
                    self.particles_handler.add_particles(particle_type, pos=pos, nb=1)

            color = (11,222,22)
            s.blit(self.gate_open_sprite, (self.rect.x, self.rect.y))
            self.particles_handler.do()

    def check_collide(self, player_rect):
        """check if the player collide with the gate"""
        if player_rect.colliderect(self.rect):
            print("+ player in gate")
            return True


    def do(self, s, keys_number, player_rect):
        """make the gate functional"""
        self.draw(s, keys_number)
        if keys_number == 0: # if the user has collect a key
            if self.check_collide(player_rect):
                return "player_in_gate"



class Ball:
    def __init__(self, start_pos, obstacles_size, sprites, s, vel="random"):
        self.start_pos = start_pos
        self.radius = obstacles_size[0] //3
        self.sprites = sprites
        self.ball_make_fire_brick_probability = 100
        self.particles_handler = ParticlesHandler(s)

        if vel == "random":
            self.x_vel = random.uniform(self.radius/30, self.radius/5) * random.choice((-1, 1))
            self.y_vel = random.uniform(self.radius/30, self.radius/5) * random.choice((-1, 1))
        else:
            self.x_vel, self.y_vel = vel

        animation_speed = random.uniform(0.05, 0.2)
        self.animation = Animation(len(sprites), speed=animation_speed)



        self.rect = pygame.Rect(self.start_pos[0], self.start_pos[1], self.radius*2, self.radius*2) # for collision
        self.pos = list(self.start_pos)


    def move(self, obstacles_list):
        # right and left
        self.pos[0] += self.x_vel
        self.rect.x = self.pos[0]

        for obstacle in obstacles_list:
            if self.rect.colliderect(obstacle.rect):
                if random.randint(0, self.ball_make_fire_brick_probability):
                    obstacle.label = "kill"
                if self.x_vel > 0: # if moving to the right
                    self.rect.right = obstacle.rect.left
                    self.pos[0] = self.rect.x
                    self.x_vel *= -1 # change the direction of the ball

                elif self.x_vel < 0: # if moving to the left
                    self.rect.left = obstacle.rect.right
                    self.pos[0] = self.rect.x
                    self.x_vel *= -1 # change the direction of the ball


        # up and down
        self.pos[1] += self.y_vel
        self.rect.y = self.pos[1]

        for obstacle in obstacles_list:
            if self.rect.colliderect(obstacle.rect):
                if random.randint(0, self.ball_make_fire_brick_probability):
                    obstacle.label = "kill"
                if self.y_vel > 0: # if moving to the right
                    self.rect.bottom = obstacle.rect.top
                    self.pos[1] = self.rect.y
                    self.y_vel *= -1 # change the direction of the ball

                elif self.y_vel < 0: # if moving to the left
                    self.rect.top = obstacle.rect.bottom
                    self.pos[1] = self.rect.y
                    self.y_vel *= -1 # change the direction of the ball


    def check_collide(self, player_rect):
        """check if the player collide with the ball"""
        if player_rect.colliderect(self.rect):
            print("- player collide ball")
            return True


    def draw(self, s):
        """draw the sprite ball on the s"""
        self.particles_handler.add_particles("fire", pos=(self.rect.centerx, self.rect.centery), to_number=40, radius=self.radius)
        self.particles_handler.do()

        self.animation.do()
        pos = (self.rect.centerx - self.sprites[0].get_width()/2, self.rect.centery - self.sprites[0].get_height()/2)
        s.blit(self.sprites[self.animation.current_frame], pos)


    def do(self, s, player, obstacles_list):
        """Make the ball functional"""
        self.draw(s)
        if not player.is_dead:
            self.move(obstacles_list)
            if self.check_collide(player.rect):
                player.is_dead = True



class Obstacle:
    """Create an obstacle that will collide with the player"""
    def __init__(self, x, y, size, sprites, label):
        self.rect = pygame.Rect(x, y, size[0], size[1]) # create the rect of the obstacles
        self.sprites = sprites
        self.label = label


    def draw(self, s):
        sprite = self.sprites[self.label]
        s.blit(sprite, (self.rect.x, self.rect.y))



class Level:
    """Create a functional level with a maze and a player"""
    def __init__(self, s, current_level_nb, sprites, menu):
        self.s = s
        self.particles_handler = ParticlesHandler(s)
        self.sprites = sprites
        self.menu = menu
        map = all_levels[current_level_nb]
        if current_level_nb == len(list(all_levels.keys())):
            self.game_finish = True
        else:
            self.game_finish = False
        self.create_maze(map)
        self.player = player.Player(self.player_pos, (self.obstacles_size[0]//1.4, self.obstacles_size[1]//1.4),
                            {"left":pygame.K_LEFT, "right":pygame.K_RIGHT, "up":pygame.K_UP, "down":pygame.K_DOWN},
                            self.sprites, self.s)


    def create_maze(self, map):
        """create the obstacles from the level map"""
        if len(map[0]) > len(map): # if the width of the level is bigger than the height
            obst_size = self.s.get_width()//len(map[0])
        else:
            obst_size = self.s.get_width()//len(map)
        self.obstacles_size = (obst_size, obst_size)

        brick_sprite = pygame.transform.scale(self.sprites["brick_sprite"], self.obstacles_size)
        brick_fire_sprite = pygame.transform.scale(self.sprites["brick_sprite_fire"], self.obstacles_size)
        brick_sprites = {"kill": brick_fire_sprite, "normal": brick_sprite}
        fireball_sprites = [pygame.transform.scale(sprite, self.obstacles_size) for sprite in self.sprites["fireball"]]

        self.obstacles_list = [] # store the obstacles
        self.keys_list = [] # store the keys
        self.gates = [] # store the gates
        self.balls = [] # store the balls


        for y_index in range(len(map)):
            for x_index in range(len(map[0])):

                x_pos = self.obstacles_size[0] * x_index
                y_pos = self.obstacles_size[1] * y_index

                if map[y_index][x_index] == '#': # Obstacle (that does NOT kill the player if collision)
                    self.obstacles_list.append(Obstacle(x_pos, y_pos, self.obstacles_size, brick_sprites, label="normal"))


                if map[y_index][x_index] == 'K': # Obstacle (that does NOT kill the player if collision)
                    self.obstacles_list.append(Obstacle(x_pos, y_pos, self.obstacles_size, brick_sprites, label="kill"))


                elif map[y_index][x_index] == 'o': # Ball moving randomly
                    self.balls.append(Ball((x_pos, y_pos), self.obstacles_size, fireball_sprites, self.s, vel="random"))

                elif map[y_index][x_index] == 'O': # Ball that move just in x pos
                    vel_x = -self.obstacles_size[0]//1.4 / 18
                    self.balls.append(Ball((x_pos, y_pos), self.obstacles_size, fireball_sprites, self.s, vel=(vel_x, 0)))

                elif map[y_index][x_index] == '0': # Ball that move just in y pos
                    vel_y = self.obstacles_size[0]//1.4 / 18
                    self.balls.append(Ball((x_pos, y_pos), self.obstacles_size, fireball_sprites, self.s, vel=(0, vel_y)))


                elif map[y_index][x_index] == "$": # keys
                    self.keys_list.append(Key(x_pos, y_pos, self.obstacles_size, self.sprites["key_sprites"], self.s))


                elif map[y_index][x_index] == "p": # player
                    self.player_pos = [x_pos, y_pos]


                elif  map[y_index][x_index] == "@": # Gate
                    self.gates.append(Gate(x_pos, y_pos, self.obstacles_size, self.sprites["gate_open_sprite"], self.sprites["gate_close_sprite"], self.s))


    def retry_and_quit_buttons(self):
        """buttons that appears when the player died"""


    def reset_level(self):
        """Reset the level"""


    def do(self):
        """make the level functional"""
        if self.game_finish:
            # particles
            if random.randint(0,3) == 1:
                particle_type = random.choice(("victory", "victory"))
                self.particles_handler.add_particles(particle_type, pos=[random.randint(0, self.s.get_width()), random.randint(-10, 0)], nb=1, radius=random.randint(4, 6)) # self.s.get_height())
            self.particles_handler.do()

        for obstacle in self.obstacles_list: # for all obstacles
            obstacle.draw(self.s) # draw the obstacles


        for key in self.keys_list:
            if key.do(self.s, self.player.rect) == "to_remove": # make the key functional
                self.keys_list.remove(key)
                break

        for gate in self.gates:
            if gate.do(self.s, len(self.keys_list), self.player.rect) == "player_in_gate":
                return "load_next_level"

        for ball in self.balls:
            ball.do(self.s, self.player, self.obstacles_list)

        self.player.do(self.obstacles_list) # make the player  functional

        # if the player has been killed
        if self.player.is_dead:
            if self.menu.retry.do(self.s)[0]:
                return "reload_level"
            if self.menu.game_menu.do(self.s)[0]:
                return "menu"

        if self.game_finish:
            # button
            if self.menu.finish.do(self.s)[0]:
                return "finish"
