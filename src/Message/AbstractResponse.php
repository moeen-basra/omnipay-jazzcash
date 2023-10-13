<?php

namespace Omnipay\Jazzcash\Message;

abstract class AbstractResponse extends \Omnipay\Common\Message\AbstractResponse
{
    protected array $responseCodes = [
        '000' => 'Thank you for Using JazzCash, your transaction was successful.',
        '001' => 'Limit exceeded',
        '002' => 'Account not found',
        '003' => 'Account inactive',
        '004' => 'Low balance',
        '014' => 'Warm card',
        '015' => 'Hot card',
        '016' => 'Invalid card status',
        '024' => 'Bad PIN',
        '055' => 'Host link down',
        '058' => 'Transaction timed out',
        '059' => 'Transaction rejected by host',
        '060' => 'PIN retries exhausted',
        '062' => 'Host offline',
        '063' => 'Destination not found',
        '066' => 'No transactions allowed',
        '067' => 'Invalid account status',
        '095' => 'Transaction rejected',
        '101' => 'Invalid merchant credentials.',
        '102' => 'Card blocked',
        '103' => 'Customer blocked',
        '104' => 'BIN not allowed for use on merchant',
        '105' => 'Transaction exceeds merchant per transaction limit.',
        '106' => 'Transaction exceeds per transaction limit for card',
        '107' => 'Transaction exceeds cycle limit for card',
        '108' => 'Authorization of customer registration required',
        '109' => 'Transaction does not exist',
        '110' => 'Invalid value for .',
        '111' => 'Transaction not allowed on Merchant/Bank.',
        '112' => 'Transaction Cancelled by User.',
        '113' => 'Transaction settlement period lapsed',
        '115' => 'Invalid hash received.',
        '116' => 'Transaction Expired',
        '117' => 'Transaction not allowed on Sub Merchant',
        '118' => 'Transaction not allowed due to maintenance.',
        '119' => 'Transaction is awaiting Reversal',
        '120' => 'Delivery status cannot be updated',
        '121' => 'Transaction has been marked confirmed by Merchant.',
        '122' => 'Reversed',
        '124' => 'Order is placed and waiting for financials to be received over the counter.',
        '125' => 'Order has been delivered',
        '126' => 'Transaction is disputed',
        '127' => 'Sorry! Transaction is not allowed due to maintenance',
        '128' => 'Awaiting action by scheme on Dispute',
        '129' => 'Transaction is dropped.',
        '131' => 'Transaction is Refunded',
        '132' => 'Sorry! The selected transaction cannot be refunded',
        '134' => 'Transaction has timed out',
        '135' => 'Invalid BIN was entered for discount',
        '157' => 'Transaction is pending.(for Mwallet and MIgs)',
        '199' => 'System error',
        '200' => 'Transaction approved – Post authorization',
        '210' => 'Authorization pending',
        '401' => 'Sorry! Your transaction could not be processed at this time, please try again after few minutes.',
        '402' => 'Your transaction was declined by your bank, please contact your bank',
        '403' => 'Your transaction has timed out, please try again',
        '404' => 'Your transaction was declined because your card is expired, please use a valid card',
        '405' => 'Your transaction was declined because of insufficient balance in your card',
        '406' => 'Sorry! Your transaction could not be processed at this time due to system error, please try again after few minutes',
        '407' => 'Sorry! Your transaction could not be processed at this time due to internal system error, please try again after few minutes',
        '408' => 'Your bank does not support internet transactions, please contact your bank',
        '409' => 'Transaction declined - do not contact issuer',
        '410' => 'You have aborted the transaction, Our team will contact you shortly to assist you or you can share your feedback with us via emailing us at :',
        '411' => 'Sorry! Your transaction is blocked due to risk, please use any other card and try again',
        '412' => 'You have aborted the transaction, Our team will contact you shortly to assist you or you can share your feedback with us via emailing us at :',
        '414' => 'Sorry! Your transaction was declined, please contact your bank',
        '415' => 'Your 3D Secure ID verification was failed, please use correct ID and try again.',
        '416' => 'Your CVV verification was failed, please use correct CVV and try again, click the help button "?" In CVV tab to find out the details of CVV.',
        '417' => 'Order locked - another transaction is in progress for this order',
        '419' => 'Your Card is not enrolled in 3D secure, please contact your bank and active your 3D secure ID',
        '421' => 'Your retry limit is exhausted, please contact your bank',
        '422' => 'Your transaction was declined due to duplication, please try again',
        '423' => 'Your transaction was declined due to address verification failed, please try again',
        '424' => 'Your transaction was declined due to wrong CVV, please try again with correct CVV, click the help button "?" In CVV tab to find out the details of CVV',
        '425' => 'Transaction declined due to address verification and card security code',
        '426' => 'Transaction declined due to payment plan, please contact your issuer bank',
        '429' => 'Your transaction has not been processed this time, please try again or contact your issuer bank.',
        '430' => 'Request_rejected',
        '431' => 'Server_failed',
        '432' => 'Server_busy',
        '433' => 'Not_enrolled_no_error_details',
        '434' => 'Not_enrolled_error_details_provided',
        '435' => 'Card_enrolled',
        '436' => 'Enrollment_status_undetermined_no_error_details',
        '437' => 'Enrollment_status_undetermined_error_details_provided',
        '438' => 'Invalid_directory_server_credentials',
        '439' => 'Error_parsing_check_enrollment_response',
        '440' => 'Error_communicating_with_directory_server',
        '441' => 'Mpi_processing_error',
        '442' => 'Error_parsing_authentication_response',
        '443' => 'Invalid_signature_on_authentication_response',
        '444' => 'Acs_session_timeout',
        '446' => 'Authentication_failed',
        '448' => 'Card_does_not_support_3ds',
        '449' => 'Authentication_not_available_no_error_details',
        '450' => 'Authentication_not_available_error_details_provided',
        '451' => 'Multiple partial refunds are not allowed.',
        '452' => 'Transaction is not eligible for refund.',
        '453' => 'Sorry! Your transaction was declined because of insufficient account balance',
        '454' => 'Partial refund is not allowed.',
        '455' => 'Refund cannot be processed as original transaction is not settled yet',
        '999' => 'Transaction failed. This response code will be sent when the transaction fails due to some technical issue at PG or Bank’s end.',
    ];

    public function getCode(): ?string
    {
        if (isset($this->data['pp_ResponseCode'])) {
            return $this->data['pp_ResponseCode'];
        }
        return null;
    }

    public function getMessage(): ?string
    {
        if (isset($this->data['pp_ResponseMessage'])) {
            return $this->data['pp_ResponseMessage'];
        }
        return null;
    }
}
