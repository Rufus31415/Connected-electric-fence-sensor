#include <ax8052.h>
#include <libmftypes.h>
#include <libmfflash.h>
#include <libmfwtimer.h>
#include <libdvk2leds.h>
#include <libmfdbglink.h>
#include <string.h>
#include <libmfadc.h>

#include "errors.h"
#include "debug.h"
#include "led.h"
#include "config.h"

#define STANDARD_DELAY() delay(-1)

// dernière mesure de tension
uint32_t voltage;

// Dernière erreur
int err;

// data à trasmettre
static uint8_t __xdata transmit_data[12];

// Entrer en mode sleep
 void sleep(void)
{
    // désactivation des GPIO
    DIRA = 0;
    PORTA = -1;

    // event A sur timer A
    WTIRQEN=1;

    WTCFGB=0x7; // disable
    WTCFGA=(SLEEP_TIME<<3) | 1; // LPOSC

    enter_sleep();
}


void init(void)
{
    DPS = 0;
    wtimer0_setclksrc(CLKSRC_LPOSC, 1);
    wtimer1_setclksrc(CLKSRC_FRCOSC, 7);

    // Disable PORTC
    DIRC = 0;
    PORTC = -1;


    DIRA = 0x07; // A0,A1,A2 en sortie
    PORTA = -1; // reset

    DIRB=0x03;
    PORTB = 0xFC;

    // Allumer toutes les LEDs au démarrage
    if(PCON == 0)
    {
        LED_GREEN = LED_ON;
        for(int i=0; i<5; i++)
        {
            delay(-1);
        }
    }
    LED_GREEN = LED_OFF;


    ANALOGA = 0x10; // A5 analog input

    MISCCTRL |= 0x02;

    EIE = 0x00;
    E2IE = 0x00;
    IE = 0x00;
    GPIOENABLE = 1;

    // rapatrier les données de calibration usine
    flash_apply_calibration();

    CLKCON = 0x00;

    wtimer_init_deepsleep();


#ifdef DEBUG
    dbglink_init();
    dbglink_writestr("Sigfox WebCloture\nID=");
    dbglink_writehex32(ONSEMI_get_id(), 8, WRNUM_PADZERO);
    dbglink_writestr(" initial PAC=");
    dbglink_writehex32(ONSEMI_get_initial_pac_hi(), 8, WRNUM_PADZERO);
    dbglink_writehex32(ONSEMI_get_initial_pac_lo(), 8, WRNUM_PADZERO);
    dbglink_tx('\n');
#endif // DEBUG

    // Enable interrupts
    EA = 1;

/*
/////// CONFIDENTIAL ///////////

Initialize radio

...
...
...
////////////////////////////////
*/


    PCON = 0x01;

    // use calibration data
    ADCTUNE1 = 0x06;
    ADCTUNE0 = 0x01;

    //ADC System clock, prescaler +1
    // 0b  x   0000 110
    ADCCLKSRC = 0x06;


    // Disable ADC channels 1,2,3
    ADCCH1CONFIG = 0xFF;
    ADCCH2CONFIG = 0xFF;
    ADCCH3CONFIG = 0xFF;

    // ADC5 single ended
    ADCCH0CONFIG = 0xCD;

    ADCCONV = 0x07;
}

// mesure le maximum du pique de tension de la cloture et retourne sa valeur
void measure(void)
{
    uint16_t max;
    uint32_t executionCounter, executionLimit;

    max = 0;
    executionCounter = 0;
    executionLimit = SCAN_TIME * 100000; // SCAN_TIME en s, period d'execution a 10µs
    ADCCONV=1;

    while(executionCounter<executionLimit)
    {
        executionCounter++;


        while((ADCCLKSRC & 0x80) == 0x80); // Attendre la fin de la conversion cad toutes les 10µs
        ADCCONV=1; // lancer une nouvelle mesure ADC

        if(ADCCH0VAL1>max) max = ADCCH0VAL1;

    }

    voltage = max;
    voltage = (voltage<<8) & 0xffff; // valeur ADC
    voltage = (voltage * 10000)>>16; // conversion en Volt cloture (1V-->2^16 ADC + pont diviseur par 10000)


#ifdef DEBUG
    dbglink_writenum32(voltage, 8, WRNUM_SIGNED);
    dbglink_writestr("V\n");
    for(int i=0; i<20; i++) delay(-1);
#endif // DEBUG

}

// trasmet les données par radio
void send(void)
{
#ifdef DEBUG
    dbglink_writestr("Send Frame\n");
#endif // DEBUG
    fmemcpy(transmit_data, &voltage, sizeof(voltage));

/*
/////// CONFIDENTIAL ///////////

Radio data transmission

...
...
...
////////////////////////////////
*/
    if(! err){
#ifdef DEBUG
        dbglink_sfxerror("SfxSendFrameF: ", err);
#endif // DEBUG

        for(int i=0; i<20; i++)
        {
            LED_ORANGE = LED_ON;
            STANDARD_DELAY();
            LED_ORANGE = LED_OFF;
            STANDARD_DELAY();
        }
        sleep();
    }
#ifdef DEBUG
    else
    {
        dbglink_writestr("OK\n");
    }
#endif // DEBUG
}


void main(void)
{
    init();

    measure();

    send();

    sleep();
}
