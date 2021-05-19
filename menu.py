import pygame
from gui import Button
import sys


class Menu:
    def __init__(self, s, NAME):
        self.s = s
        self.NAME = NAME
        self.bg_color = (22,22,22)
        # fonts
        self.font = pygame.font.Font("Assets/Gladifilthefte.ttf", 124)

        button_y = 400 # the first button y pos
        button_h = 60 # the height of  the buttons
        button_w = 240 # the width of the buttons
        space = 30
        self.button_c = Button(self.s.get_width()//2, button_y-button_h-space, width=button_w, height=button_h, text="Continue")
        self.button_start = Button(self.s.get_width()//2, button_y, width=button_w, height=button_h)
        button_y += button_h + space
        self.button_quit= Button(self.s.get_width()//2, button_y, width=button_w, height=button_h)
        button_y += button_h + space

        # the button to go in menu from the game
        self.retry = Button(self.s.get_width()//2, 460, width=button_w,
                                            height=button_h, text="Retry")
        self.game_menu = Button(self.s.get_width()//2, 460 + button_h + space,
                                            width=button_w, height=button_h, text="Menu")
        self.finish = Button(self.s.get_width()//2, 140 + button_h + space,
                                            width=button_w, height=button_h, text="Continue")

    def title(self):
        text = self.NAME
        lbl_w= self.font.render(f"{text}", 1, (0,0,0))
        lbl_w= lbl_w.get_width()
        
        x = self.s.get_width()//2 - lbl_w//2
        y = 84
        color = [70,44,111]

        for letter in text:
            # make the text shadow
            shadow= self.font.render(f"{letter}", 1, (222,222,222))
            self.s.blit(shadow, (x-3,y+2))
            # text
            lbl = self.font.render(f"{letter}", 1, color)
            self.s.blit(lbl, (x,y))
            # change the position of the next letter
            x += lbl.get_width()
            # change the color of the next letter
            color[2] -= 5
            color[2] = (max(0, color[2])) #make that the color can not be lower than 0
            color[0] += 25
            color[0] = (min(255, color[0])) # make  that the color can not be bigger than 255


    def do_from_menu(self):
        """The menu if the user is on the menu (before the game)"""
        global state, current_level, level
        self.s.fill(self.bg_color)
        self.title()

        if self.button_c.do(self.s)[0]:
            return "continue"

        self.button_start.text = "Start new game"
        if self.button_start.do(self.s)[0]:
            print("Starting the Game")
            return "start"


        self.button_quit.text = "Quit"
        if self.button_quit.do(self.s)[0]: # if the user press on the button 2
            # close the game
            pygame.quit()
            sys.exit()


    def do_from_game(self):
        """The menu if the user is the game (in the maze)"""
        global state
        SCREEN.fill(self.bg_color)

        self.button_start.text =  "Return in game"
        if self.button_start.do(self.s)[0]: # if the user press on the button 1
            state = "game"

        self.button_quit.text =  "Quit game"
        if self.button_quit.do(self.s)[0]: # if the user press on the button 2
            state = "menu"
