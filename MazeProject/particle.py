import pygame
import time
import random


class Particle:
    def __init__(self, s_size, start_pos, mode):
        self.s_size = s_size
        self.pos = list(start_pos)
        self.radius = mode["radius"]
        self.decrease_radius_value = mode["decrease_radius_value"]
        self.color = mode["color"]

        if mode["vel"] == "default":
            self.vel = {"x": {"current":  random.uniform(-4, 4), "change": 0, "max": 10, "min": -10},
                         "y": {"current":  random.uniform(-4, 4), "change": 0, "max": 10, "min": -10}}
        else:
            self.vel = mode["vel"]


    def move(self):
        self.pos[0] += self.vel["x"]["current"]
        if self.pos[0] - self.radius > self.s_size[0] or self.pos[0] + self.radius < 0:
            return "to_kill"
        self.pos[1] += self.vel["y"]["current"]
        if self.pos[1] - self.radius > self.s_size[1] or self.pos[1] + self.radius < 0:
            return "to_kill"


    def decrease_radius(self):
        self.radius -= self.decrease_radius_value
        if self.radius <= 2:
            return "to_kill"


    def change_velocity(self):
        # change the vel
        # make that the vel is not lower or bigger than the min and max
        for direction in ("x", "y"):
           self.vel[direction]["current"] +=  self.vel[direction]["change"]
           if self.vel[direction]["change"] != 0:
                if self.vel[direction]["current"] < self.vel[direction]["min"]:
                    self.vel[direction]["current"] = self.vel[direction]["min"]
                elif self.vel[direction]["current"] > self.vel[direction]["max"]:
                    self.vel[direction]["current"] = self.vel[direction]["max"]


    def change_color(self):
        pass


    def update(self):
        if self.move() == "to_kill":
            return "to_kill"
        self.change_velocity()
        if self.decrease_radius() == "to_kill":
            return "to_kill"




    def draw(self, s):
        pygame.draw.circle(s, self.color,(int(self.pos[0]), int(self.pos[1])), int(self.radius))




class ParticlesHandler:
    def __init__(self, s):
        self.s = s
        self.s_size = (s.get_width(), s.get_height())
        self.particles = [] # store all particles
        self.infos = {"nb_now": 0, "nb_all": 0}


    # settings of differents mode of particles
    def update_modes(self):
            modes = {
               "blood": {"radius": random.uniform(6, 16),
                         "color": (random.randint(155,255), random.randint(11,33), random.randint(11,33)),
                         "decrease_radius_value": random.uniform(0.2, 0.8),
                         "vel": "default"
                },

                "key": {"radius": random.uniform(2, 4.4),
                          "color": (random.randint(205,210), random.randint(156,162), random.randint(29,34)),
                          "decrease_radius_value": random.uniform(0.008, 0.015),
                          "vel": {"x": {"current":  random.uniform(-0.5, 0.5), "change": 0, "max": 10, "min": -10},
                                   "y": {"current":  random.uniform(-0.5, 0.5), "change": 0, "max": 10, "min": -10}}
                },

                "fire": {"radius": random.uniform(12, 22),
                          "color": (random.randint(240,255), random.randint(120,126), random.randint(33,34)),
                          "decrease_radius_value": random.uniform(0.3, 0.5),
                          "vel": {"x": {"current":  random.uniform(-0.1, 0.1), "change": 0, "max": 10, "min": -10},
                                   "y": {"current":  random.uniform(-0.1, 0.1), "change": 0, "max": 10, "min": -10}}
                },

                "gate_green": {"radius": random.uniform(2, 6),
                          "color": (61, 242, 76),
                          "decrease_radius_value": random.uniform(0.05, 0.07),
                          "vel": {"x": {"current":  0, "change": 0, "max": 0, "min": 0},
                                   "y": {"current":  0, "change": 0, "max": 0, "min": 0}}
                },

                "gate_purple": {"radius": random.uniform(3, 6),
                          "color": (185, 81, 237),
                          "decrease_radius_value": random.uniform(0.01, 0.06),
                          "vel": {"x": {"current":  0, "change": 0, "max": 0, "min": -0},
                                   "y": {"current":  0, "change": 0, "max": 0, "min": -0}}
                },
                "victory": {"radius": random.uniform(3, 6),
                          "color": (random.randint(44,244), random.randint(44,244), random.randint(44,222)),
                          "decrease_radius_value": random.uniform(0, 0),
                          "vel": {"x": {"current":  0, "change": 0, "max": 0, "min": 0},
                                   "y": {"current":  random.uniform(0.5, 3), "change": 0.001, "max": 5, "min": 0}}
                },

                "menu": {"radius": random.uniform(12, 22),
                          "color": (random.randint(240,255), random.randint(120,126), random.randint(33,34)),
                          "decrease_radius_value": random.uniform(0.3, 0.5),
                          "vel": {"x": {"current":  random.uniform(-0.1, 0.1), "change": 0, "max": 10, "min": -10},
                                   "y": {"current":  random.uniform(-2, 0.1), "change": 0, "max": 10, "min": -10}}
                },

                "menu2": {"radius": random.uniform(6, 12),
                          "color": random.choice(((random.randint(200,240), random.randint(133,177), random.randint(12,34)), (79, 79, 68))),
                          "decrease_radius_value": random.uniform(0.1, 0.2),
                          "vel": {"x": {"current":  random.uniform(-0.1, 0.1), "change": 0, "max": 10, "min": -10},
                                   "y": {"current":  random.uniform(-3, 0.1), "change": 0, "max": 10, "min": -10}}
                }
            }

            return modes


    def add_particles(self, mode, pos="center", nb=0, to_number=False, radius="default"):
        """
        to_number -> will create some new particles until the to_number value is reach
        """
        if pos == "center":
            pos = (self.s.get_width()//2, self.s.get_height()//2)


        for i in range(nb):
            modes = self.update_modes()
            if radius != "default":
                modes[mode]["radius"] = radius
            self.particles.append(Particle(self.s_size, pos, modes[mode]))
            self.infos["nb_all"] += 1

        if to_number is not False:
            while to_number > len(self.particles):
                modes = self.update_modes()
                if radius != "default":
                    modes[mode]["radius"] = radius
                self.particles.append(Particle(self.s_size, pos, modes[mode]))
                self.infos["nb_all"] += 1


    def do(self):
        for particle in self.particles:
            if particle.update() == "to_kill": # if should be removed
                self.particles.pop(self.particles.index(particle))
            else:
                particle.draw(self.s)
        self.infos["nb_now"] = len(self.particles)
