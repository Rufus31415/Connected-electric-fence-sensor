#ifndef LED_H_INCLUDED
#define LED_H_INCLUDED

#include <ax8052.h>

#define LED_RED PORTA_2
#define LED_ORANGE PORTA_1
#define LED_GREEN PORTA_0

#define LED_RADIO   PORTB_1
#define LED_CPU   PORTB_2

#define LED_RED_ON() PORTA_0 = 0
#define LED_ORANGE_ON() PORTA_1 = 0
#define LED_GREEN_ON() PORTA_2 = 0

#define LED_RED_OFF() PORTA_0 = 1
#define LED_ORANGE_OFF() PORTA_1 = 1
#define LED_GREEN_OFF() PORTA_2 = 1



#define LED_ON  0
#define LED_OFF 1

#endif // LED_H_INCLUDED
