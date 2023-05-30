<?php

    class GeneralReservationException extends Exception {
        public function __construct($message, $code, $previous = null)
        {
            parent::__construct($message, $code, $previous);
        }
    }

    class UnknownException extends Exception {
        public function __construct($code, $previous = null)
        {
            $message = "<p style=\"color: red;\">(" . $code . ") an unidentified error occured. Please try again. If the issue persists, please contact an administrator</p>";
            parent::__construct($message, $code, $previous);
        }
    }

    class InvalidIDException extends GeneralReservationException {
        public function __construct($InvalidUserID, $code = 1045, $previous = null)
        {
            $message = "<p style=\"color: red;\">Invalid UserID (" . $InvalidUserID . "), please check numerical value and try again</p>";
            parent::__construct($message, $code, $previous);
        }

    }

    class NoIDException extends GeneralReservationException {
        public function __construct($message = "<p style=\"color: red;\">Please enter a UserID</p>", $code = 1046, $previous = null)
        {
            parent::__construct($message, $code, $previous);
        }

    }

    class FailedReservationException extends GeneralReservationException {
        public function __construct($seatName, $code = 1000, $previous = null)
        {
            $message = "<p style=\"color: red;\">" . $seatName . " is no longer available. Please select a different seat</p>";
            parent::__construct($message, $code, $previous);
        }

    }

    class DuplicateIDReservationException extends GeneralReservationException {
        public function __construct($userID, $code = 1001, $previous = null)
        {
            $message = "<p style=\"color: red;\">The given UserID (" . $userID . "), has already reserved a seat</p>";
            parent::__construct($message, $code, $previous);
        }

    }

    class InvalidSeatException extends GeneralReservationException {
        public function __construct($message = "<p style=\"color: red;\">Invalid Seat. Please select a valid seat and try again</p>", $code = 1054, $previous = null)
        {
            parent::__construct($message, $code, $previous);
        }

    }


?>
