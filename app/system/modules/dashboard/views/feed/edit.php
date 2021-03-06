<div class="uk-form-row">
    <label for="form-feed-title" class="uk-form-label"><?= __('Title') ?></label>
    <div class="uk-form-controls">
        <input id="form-feed-title" class="uk-form-width-large" type="search" name="widget[title]" value="<?= $widget->get('title') ?>" required>
    </div>
</div>

<div class="uk-form-row">
    <label for="form-feed-url" class="uk-form-label"><?= __('URL') ?></label>
    <div class="uk-form-controls">
        <input id="form-feed-url" class="uk-form-width-large" type="search" name="widget[url]" value="<?= $widget->get('url') ?>">
    </div>
</div>

<div class="uk-form-row">
    <label for="form-feed-count" class="uk-form-label"><?= __('Number of Posts') ?></label>
    <div class="uk-form-controls">
        <select id="form-weather-count" class="uk-form-width-large" name="widget[count]">
            <?php foreach ([1,2,3,4,5,6,7,8,9,10] as $value): ?>
            <option value="<?= $value ?>" <?= $widget->get('count') == $value ? 'selected' : '' ?>><?= $value ?></option>
            <?php endforeach ?>
        </select>
    </div>
</div>

<div class="uk-form-row">
    <span class="uk-form-label"><?= __('Post Content') ?></span>
    <div class="uk-form-controls uk-form-controls-text">
        <p class="uk-form-controls-condensed">
            <label><input type="radio" name="widget[content]" value="0" <?= $widget->get('content') == '0' || $widget->get('content') == '' ? 'checked' : '' ?>> <?= __('Don\'t show') ?></label>
        </p>
        <p class="uk-form-controls-condensed">
            <label><input type="radio" name="widget[content]" value="1" <?= $widget->get('content') == '1' ? 'checked' : '' ?>> <?= __('Show on all posts') ?></label>
        </p>
        <p class="uk-form-controls-condensed">
            <label><input type="radio" name="widget[content]" value="2" <?= $widget->get('content') == '2' ? 'checked' : '' ?>> <?= __('Only show on first post.') ?></label>
        </p>
    </div>
</div>