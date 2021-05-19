# Setup Python ----------------------------------------------- #
import pygame
import sys
import os
import random

import world
from particle import ParticlesHandler
import menu as me


# Setup pygame/window ---------------------------------------- #
os.environ['SDL_VIDEO_WINDOW_POS'] = "%d,%d" % (100,32) # windows position
pygame.init()
NAME = "Maze Game"
pygame.display.set_caption(NAME)
SCREEN_WIDTH = 680
SCREEN_HEIGHT = 680
SCREEN = pygame.display.set_mode((SCREEN_WIDTH, SCREEN_HEIGHT),0,32)
pygame.mouse.set_visible(False)
mainClock = pygame.time.Clock()

# Music ----------------------------------------------------------#
pygame.mixer.music.load("Assets/game_music.mp3")
pygame.mixer.music.set_volume(0.5)
pygame.mixer.music.play(-1) 
# Variables ------------------------------------------------------- #
state = "menu"
current_level_nb = 1 # the level at which the user starts
admin_mod = True # allow the user to press Enter to go to the next level


# Constantes -------------------------------------------------------#
BG_COLOR = (22,22,22)


# Load ---------------------------------------------------------------#
    # Sprites
sprites = {}
sprites["gate_open_sprite"] = pygame.image.load("Assets/gate_open.png").convert_alpha()
sprites["gate_close_sprite"] = pygame.image.load("Assets/gate_close.png").convert_alpha()
sprites["key_sprite"] = pygame.image.load("Assets/key.png").convert_alpha()
sprites["key_sprites"] = [pygame.image.load(f"Assets/key/key_{i}.png").convert_alpha() for i in range(9)]
sprites["player_sprites_alive"] = [pygame.image.load(f"Assets/player/player_{i}.png").convert_alpha() for i in range(8)]
sprites["player_sprites_dead"] = pygame.image.load(f"Assets/player/player_dead.png").convert_alpha()
sprites["brick_sprite"] = pygame.image.load("Assets/brick_2.png").convert_alpha()
sprites["brick_sprite_fire"] = pygame.image.load("Assets/brick_fire.png").convert_alpha()
sprites["fireball"] = [pygame.image.load(f"Assets/fireball/FB500-{i}.png").convert_alpha() for i in range(1, 6)]


# Creation ---------------------------------------------------------#
menu = me.Menu(SCREEN, NAME)
particles_handler = ParticlesHandler(SCREEN)

# Functions ------------------------------------------------------- #
def load_level():
    """load the current level"""
    global level
    write_save(current_level_nb)
    level = world.Level(SCREEN, current_level_nb, sprites, menu)


def load_next_level():
    """load next level"""
    global current_level_nb
    current_level_nb += 1
    write_save(current_level_nb)
    load_level()


def read_save():
    """read the player previous game stats of the text file"""
    with open("save.txt", 'r') as file: # open and read the save file
        save = [line.split(",") for line in file.read().splitlines()]
    return save

def write_save(to_write):
     with open("save.txt", 'w') as file: # open and write the level at which the player is
        file.write(str(to_write))

def redraw():
    global state, current_level_nb
    SCREEN.fill(BG_COLOR) # make the background

    if state == "menu": # if the user is in the menu
        menu_output = menu.do_from_menu()
        m_pos =  pygame.mouse.get_pos()
        if random.randint(0,4) == 1:
            particles_handler.add_particles("menu2", pos=m_pos, nb=1)
        particles_handler.add_particles("menu", pos=m_pos, nb=2)
        particles_handler.do()
        if menu_output == "start": # if the button is pressed
            pygame.mouse.set_visible(True)
            state = "game"
            current_level_nb = 1
            load_level() # create the first level
        if menu_output == "continue":
            current_level_nb = read_save()[0][0]
            current_level_nb = int(current_level_nb)
            pygame.mouse.set_visible(True)
            state = "game"
            load_level()

    elif state == "game": # if the user is in the game
        level_output = level.do()
        if level_output == "load_next_level":
            load_next_level()
        elif level_output == "reload_level":
            load_level()
        elif level_output == "menu":
            pygame.mouse.set_visible(False)
            state = "menu"
        elif level_output == "finish":
            pygame.mouse.set_visible(False)
            state = "menu"


def buttons():
    user_left_click = False
    for event in pygame.event.get():
        if event.type == pygame.QUIT:
            pygame.quit()
            sys.exit()
        if event.type == pygame.KEYDOWN:
            if event.key == pygame.K_ESCAPE:
                pygame.quit()
                sys.exit()
            if event.key == pygame.K_RETURN and admin_mod:
                print("-> admin next level")
                load_next_level()

def update():
    pygame.display.update()
    mainClock.tick(90)


# Loop ------------------------------------------------------- #
while True:

    # Buttons ------------------------------------------------ #
    buttons()

    # draw --------------------------------------------- #
    redraw()


    # Update ------------------------------------------------- #
    update()
