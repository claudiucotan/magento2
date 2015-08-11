<?php
/**
 * Copyright © 2015 Magento. All rights reserved.
 * See COPYING.txt for license details.
 */

namespace Magento\Theme\Test\Block;

use Magento\Mtf\Block\Block;
use Magento\Mtf\Client\Locator;

/**
 * Page Top Links block.
 */
class Links extends Block
{
    /**
     * Locator value for qty of Products in Compare list.
     *
     * @var string
     */
    protected $qtyCompareProducts = '.compare .counter.qty';

    /**
     * Locator value for correspondent link.
     *
     * @var string
     */
    protected $link = '//a[contains(text(), "%s")]';

    /**
     * Locator value for welcome message.
     *
     * @var string
     */
    protected $welcomeMessage = '.greet.welcome';

    /**
     * Locator value for "Expand/Collapse Customer Menu" button.
     *
     * @var string
     */
    protected $toggleButton = '[data-action="customer-menu-toggle"]';

    /**
     * Locator value for Customer Menu.
     *
     * @var string
     */
    protected $customerMenu = '.customer-menu > ul';

    /**
     * Expand Customer Menu (located in page Header) if it was collapsed.
     *
     * @return void
     */
    protected function expandCustomerMenu()
    {
        if (!$this->_rootElement->find($this->customerMenu)->isVisible()) {
            $this->_rootElement->find($this->toggleButton)->click();
        }
    }

    /**
     * Open link by its title.
     *
     * @param string $linkTitle
     * @return void
     */
    public function openLink($linkTitle)
    {
        $this->expandCustomerMenu();
        $this->_rootElement->find(sprintf($this->link, $linkTitle), Locator::SELECTOR_XPATH)->click();
    }

    /**
     * Verify if correspondent link is present or not.
     *
     * @param string $linkTitle
     * @return bool
     */
    public function isLinkVisible($linkTitle)
    {
        $this->expandCustomerMenu();
        return $this->_rootElement->find(sprintf($this->link, $linkTitle), Locator::SELECTOR_XPATH)->isVisible();
    }

    /**
     * Wait until correspondent link appears.
     *
     * @param string $linkTitle
     * @return void
     */
    public function waitLinkIsVisible($linkTitle)
    {
        $browser = $this->_rootElement;
        $selector = sprintf($this->link, $linkTitle);
        $browser->waitUntil(
            function () use ($browser, $selector) {
                $element = $browser->find($selector, Locator::SELECTOR_XPATH);
                return $element->isVisible() ? true : null;
            }
        );
    }

    /**
     * Get qty of Products in Compare list.
     *
     * @return string
     */
    public function getQtyInCompareList()
    {
        $this->waitForElementVisible($this->qtyCompareProducts);
        $compareProductLink = $this->_rootElement->find($this->qtyCompareProducts);
        preg_match_all('/^\d+/', $compareProductLink->getText(), $matches);
        return $matches[0][0];
    }

    /**
     * Get url from link.
     *
     * @param string $linkTitle
     * @return string
     */
    public function getLinkUrl($linkTitle)
    {
        $link = $this->_rootElement->find(sprintf($this->link, $linkTitle), Locator::SELECTOR_XPATH)
            ->getAttribute('href');

        return trim($link);
    }

    /**
     * Wait until welcome message appears.
     *
     * @return void
     */
    public function waitWelcomeMessage()
    {
        $this->waitForElementVisible($this->welcomeMessage);
    }
}
