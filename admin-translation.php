<?php
/**
 * @var The99Bitcoins_BtcFaucet_Plugin $this
 * @var array $translation
 * @var string $notice_message
 * @var string $notice_css_class
 */
?>

    <?php if (!empty($notice_message)): ?>
        <div class="<?= $notice_css_class ?>">
            <p><?= $notice_message ?></p>
        </div>
    <?php endif ?>

    <form method="post" novalidate="novalidate" enctype="multipart/form-data">

        <table class="form-table">
            <?php foreach (array_unique(self::$translation) as $string): ?>
            <tr>
                <th scope="row">
                    <label for="translation[<?= esc_attr($string) ?>]"><?= esc_html($string) ?></label>
                </th>
                <td>
                    <input class="regular-text" type="text" name="translation[<?= esc_attr($string) ?>]" id="translation[<?= esc_attr($string) ?>]" value="<?= isset($translation[$string]) ? esc_attr($translation[$string]) : '' ?>" placeholder="<?= esc_attr($string) ?>">
                </td>
            </tr>
            <?php endforeach ?>
        </table>

        <?php submit_button() ?>
    </form>
</div>
