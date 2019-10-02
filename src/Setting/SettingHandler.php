<?php
/**
 * This file is part of GrrSf application
 * @author jfsenechal <jfsenechal@gmail.com>
 * @date 2/10/19
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 *
 */

namespace App\Setting;

use App\Entity\Setting;
use App\Manager\SettingManager;
use App\Repository\SettingRepository;

class SettingHandler
{
    /**
     * @var SettingFactory
     */
    private $settingFactory;
    /**
     * @var SettingRepository
     */
    private $settingRepository;
    /**
     * @var SettingManager
     */
    private $settingManager;

    public function __construct(
        SettingFactory $settingFactory,
        SettingRepository $settingRepository,
        SettingManager $settingManager
    ) {
        $this->settingFactory = $settingFactory;
        $this->settingRepository = $settingRepository;
        $this->settingManager = $settingManager;
    }

    public function handleEdit($data)
    {
        foreach ($data as $name => $value) {
            $setting = $this->settingRepository->findOneBy(['name' => $name]);
            if (!$setting) {
                $this->handleNewSetting($name, $value);
                continue;
            }
            $this->handleExistSetting($setting, $value);
            continue;
        }

        $this->settingManager->flush();
    }

    protected function handleNewSetting($name, $value)
    {
        if (null === $value) {
            return;
        }

        $value = $this->handleValue($value);

        $setting = $this->settingFactory->createNew($name, $value);
        $this->settingManager->persist($setting);
    }

    protected function handleExistSetting(Setting $setting, $value)
    {
        if (null === $value) {
            $this->settingManager->remove($setting);

            return;
        }
        $value = $this->handleValue($value);
        $setting->setValue($value);
    }

    protected function handleValue($value)
    {
        if (is_array($value)) {
            $value = serialize($value);
        }

        return $value;
    }

}