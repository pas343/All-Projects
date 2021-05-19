import time
import random

class Animation:
    def __init__(self, nb_frame, speed=0.2, start_frame="random", bouncing=False):
        self.speed = speed
        self.nb_frame = nb_frame
        self.timer = 0
        self.bouncing = bouncing # make that the animation go backward when after the last frame
        self.direction = 1

        if start_frame == "random":
            self.current_frame = random.randint(0, self.nb_frame-1)
        else:
            self.current_frame = 0


    def do(self):
        """return the index of the frame"""

        t = time.time()
        if t > self.timer:
            self.timer = t + self.speed

            if not self.bouncing: 
                self.current_frame += 1 # change the current frame

                if self.current_frame > self.nb_frame -1:
                    self.current_frame = 0
            else:
                self.current_frame += self.direction

                if self.current_frame > self.nb_frame -1:
                    self.current_frame = self.nb_frame - 2
                    self.direction *= -1
                elif self.current_frame < 0:
                    self.current_frame = 1
                    self.direction *= -1
