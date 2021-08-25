<?php

namespace IWD\PaypalPos\Model\Theme;

use Magento\Theme\Block\Html\Header\Logo as LogoBlock;
use Magento\Store\Model\StoreManagerInterface;
use Magento\Store\Model\App\Emulation;

class Logo
{
    /**
     * @var LogoBlock
     */
    private $logoBlock;
    /**
     * @var StoreManagerInterface
     */
    private $storeManager;
    /**
     * @var Emulation
     */
    private $appEmulation;

    /**
     * Logo constructor.
     * @param LogoBlock $logoBlock
     * @param StoreManagerInterface $storeManager
     * @param Emulation $appEmulation
     */
    public function __construct(
        LogoBlock $logoBlock,
        StoreManagerInterface $storeManager,
        Emulation $appEmulation
    ) {
        $this->logoBlock = $logoBlock;
        $this->storeManager = $storeManager;
        $this->appEmulation = $appEmulation;
    }

    /**
     * @return mixed
     * @throws \Exception
     */
    public function getLogoSrc()
    {
        $storeId = $this->storeManager->getStore()->getId();
        $this->appEmulation->startEnvironmentEmulation($storeId, \Magento\Framework\App\Area::AREA_FRONTEND, true);
        return $this->getSrc();
    }

    /**
     * @return string
     */
    public function getSrc()
    {
        return $this->logoBlock->getLogoSrc();
    }
}
