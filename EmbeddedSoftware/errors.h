#ifndef ERRORS_H_INCLUDED
#define ERRORS_H_INCLUDED


#include "errors.h"


/*!
 * \fn void dbglink_sfxerror(const char __code *msg, sfx_error_t err)
 * \brief Display an error message with decoded error code on debuglink.
 *
 * \param[in] const char __code *msg               the Message
 * \param[in] sfx_error_t err                      the Error Code
 */
static void dbglink_sfxerror(const char __code *msg, int err)
{
#ifdef DEBUG
    const struct sigfox_errors __code *p = sigfox_errors;
    dbglink_writestr(msg);
    for (;; ++p) {
        if (p->err == err) {
            dbglink_writestr(p->msg);
            dbglink_tx('\n');
            return;
        }
        if (p->err == SFX_ERR_NONE)
            break;
    }
    dbglink_writestr("unknown error ");
    dbglink_writenum16(err, 0, 0);
    dbglink_tx('\n');
#endif // DEBUG
}


#endif // ERRORS_H_INCLUDED
