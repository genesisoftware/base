<?php
namespace Genesisoft\Base\Preference\Magento\Checkout\Model;

/**
 * Class TotalsInformationManagement
 */
class TotalsInformationManagement extends \Magento\Checkout\Model\TotalsInformationManagement
{
    /**
     * {@inheritDoc}
     */
    public function calculate(
        $cartId,
        \Magento\Checkout\Api\Data\TotalsInformationInterface $addressInformation
    ) {
        /** @var \Magento\Quote\Model\Quote $quote */
        if (!empty($cartId)) {
            $quote = $this->cartRepository->get($cartId);
        }

        if (!empty($quote)) {

            $this->validateQuote($quote);

            if ($quote->getIsVirtual()) {
                $quote->setBillingAddress($addressInformation->getAddress());
            } else {
                $quote->setShippingAddress($addressInformation->getAddress());
                $quote->getShippingAddress()->setCollectShippingRates(true)->setShippingMethod(
                    $addressInformation->getShippingCarrierCode() . '_' . $addressInformation->getShippingMethodCode()
                );
            }
            $quote->collectTotals();
        } else {
            return;
        }

        return $this->cartTotalRepository->get($cartId);
    }
}
