<?php
declare(strict_types=1);

namespace App\Test\Fixture;

use Cake\I18n\FrozenTime;
use Cake\TestSuite\Fixture\TestFixture;

/**
 * SnippetsFixture
 */
class SnippetsFixture extends TestFixture
{
    /**
     * Init method
     *
     * @return void
     */
    public function init(): void
    {
        $this->records = [
            [
                'id' => 1,
                'user_id' => 1,
                'content' => 'Lorem ipsum dolor sit amet, aliquet feugiat. Convallis morbi fringilla gravida, phasellus feugiat dapibus velit nunc, pulvinar eget sollicitudin venenatis cum nullam, vivamus ut a sed, mollitia lectus. Nulla vestibulum massa neque ut et, id hendrerit sit, feugiat in taciti enim proin nibh, tempor dignissim, rhoncus duis vestibulum nunc mattis convallis.',
                'expire' => (new FrozenTime('+3 days'))->i18nFormat('yyyy-MM-dd HH:mm:ss'),
                'created' => FrozenTime::now()->i18nFormat('yyyy-MM-dd HH:mm:ss')
            ],
            [
                'id' => 2,
                'user_id' => 1,
                'content' => 'Lorem ipsum dolor sit amet, aliquet feugiat. Convallis morbi fringilla gravida, phasellus feugiat dapibus velit nunc, pulvinar eget sollicitudin venenatis cum nullam, vivamus ut a sed, mollitia lectus. Nulla vestibulum massa neque ut et, id hendrerit sit, feugiat in taciti enim proin nibh, tempor dignissim, rhoncus duis vestibulum nunc mattis convallis.',
                'expire' => (new FrozenTime('-3 days'))->i18nFormat('yyyy-MM-dd HH:mm:ss'),
                'created' => FrozenTime::now()->i18nFormat('yyyy-MM-dd HH:mm:ss')
            ],
            [
                'id' => 3,
                'user_id' => 2,
                'content' => 'Lorem ipsum dolor sit amet, aliquet feugiat. Convallis morbi fringilla gravida, phasellus feugiat dapibus velit nunc, pulvinar eget sollicitudin venenatis cum nullam, vivamus ut a sed, mollitia lectus. Nulla vestibulum massa neque ut et, id hendrerit sit, feugiat in taciti enim proin nibh, tempor dignissim, rhoncus duis vestibulum nunc mattis convallis.',
                'expire' => (new FrozenTime('+3 days'))->i18nFormat('yyyy-MM-dd HH:mm:ss'),
                'created' => FrozenTime::now()->i18nFormat('yyyy-MM-dd HH:mm:ss')
            ]
        ];
        parent::init();
    }
}
