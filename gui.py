import pygame


class Button: # make a clickable button
    def __init__(self, center_pos_x, pos_y, width=80, height=80, text="", font=None,
                 color= (144, 22, 44), color_mouse_on=(188, 44, 66), text_color=(222,222,222)):

        self.rect = pygame.Rect(center_pos_x-width//2, pos_y, width, height)
        self.color = color
        self.color_mouse_on = color_mouse_on
        self.text_color = text_color
        self.text = text
        self.font = font if None else pygame.font.Font("Assets/Gladifilthefte.ttf", 44)


    def do(self, s):
        click_on_button = False # variable that store if the user click on the button
        if self.rect.collidepoint(pygame.mouse.get_pos()): # if the user mouse is on the button
            color = self.color_mouse_on # change the color of the button
            if pygame.mouse.get_pressed()[0]: # if the user click on the button
                click_on_button = True
        else:
            color = self.color
        # draw the button
        pygame.draw.rect(s, color, self.rect)
        # write the text
        if self.text != "" and self.font != None:
            text_label = self.font.render(f"{self.text}", 1, self.text_color)
            s.blit(text_label, (self.rect.centerx-text_label.get_width()//2, self.rect.centery-text_label.get_height()//2))
        # return if the user hsa click on the button
        return (click_on_button, self.text)
