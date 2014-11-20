Internationalization Module for PHPixie
====================
* Simple to use
* No lang param in url for default language
* Params in translations
* Auto routes modify

To setup this module:

* Define this package in "require" section of *composer.json*
```
"phpixie/amalgama": "2.*@dev"
```
* Update packages
```
php composer.phar update -o  --prefer-dist
```
* Add a config file under */assets/config/amalgama.php*
```
return array(
  // The list of languages
  'list' => array('en', 'ru', 'kk'),
  // Default language
  'default' => 'en',
  // For using autorouting extension
  'autorouting' => true,
  // Names of routes for except them from autorouting extension
  'autoroutingExcept' => '^admin_'
);
```
* Expand your *Pixie.php*
```
namespace App;

class Pixie extends \PHPixie\Amalgama\Pixie {
  ...
  protected function after_bootstrap() {
    parent::after_bootstrap();
  }
}
```
* Define module in your *Pixie.php*
```
protected $modules = array(
  ...
  'amalgama' => '\PHPixie\Amalgama'
);
```
* Expand your base controller *Pixie.php*
```
<?php

namespace App;

class Page extends \PHPixie\Amalgama\Controller {

  public function before() {
    parent::before();
    ...
  }

  ...

}
```
* Define routes(If you don't want use autorouting extension)
```
'default' => array(
  array('(/<lang>)(/<controller>(/<action>(/<id>)))', array('lang' => '(en|ru)')
  array(
    'controller' => 'hello',
    'action' => 'index',
    'lang' => 'en'
  ),
),
```
* Add translations files under */assets/config/amalgama*
```
//ru.php
<?php

return array(
  'Hello World!' => 'Привет мир!',
  'Hello <?>!' => 'Привет <?>!'
);
```
To use this module:
```
// in view
<div><?php $__('Hello World!; ?></div>
<div><?php $__('Hello <?>!', array('Nxeed'); ?></div>
```
```
// lang switcher
<?php foreach($this->helper->getLangList() as $lang) : ?>
  <?php if ($lang == $this->helper->getCurrentLang()) : ?>
    <span><?php echo $lang; ?></span>
  <?php else: ?>
    <a href="<?php echo $this->helper->langSwitchLink($lang); ?>"><?php echo $lang; ?></a>
  <?php endif; ?>
<?php endforeach; ?>
```
```
// examle of using paginate module
...
$page = $this->request->param('page');

$quotes = $this->pixie->orm->get('comment');
$pager = $this->pixie->paginate->orm($quotes, $page, 10);
$pager->set_url_route('comments', array('lang' => $this->lang));
...
```
```
// validation
$validator->field('username')
  ->rule('filled')
  ->error($this->__('Field <?> must not be empty', array($this->__('username'))));
```
