#ifndef DEBUG_H_INCLUDED
#define DEBUG_H_INCLUDED

#include <libmfdbglink.h>


#ifdef DEBUG
    #define dbg_writestr(msg)   dbglink_writestr(msg)
#else
    #define dbg_writestr(msg)   ;

#endif // DEBUG

#endif // DEBUG_H_INCLUDED
