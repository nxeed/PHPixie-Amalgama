Internationalization Module for PHPixie
====================
* Simple to use
* No lang param in url for default language
* Params in translations
* Auto routes modify

Setup
--------------------
* Define this package in "require" section of *composer.json*
``` json
"phpixie/amalgama": "2.*@dev"
```
* Update packages
``` bash
php composer.phar update -o  --prefer-dist
```
* Add a config file under */assets/config/amalgama.php*
``` php
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
* Override your *Pixie.php*
``` php
namespace App;

class Pixie extends \PHPixie\Amalgama\Pixie {
  ...
  protected function after_bootstrap() {
    parent::after_bootstrap();
  }
}
```
* Define module in your *Pixie.php*
``` php
protected $modules = array(
  ...
  'amalgama' => '\PHPixie\Amalgama'
);
```
* Override your base controller
``` php
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
``` php
'default' => array(
  array('(/<lang>)(/<controller>(/<action>(/<id>)))', array('lang' => '(en|ru)')
  array(
    'controller' => 'hello',
    'action' => 'index',
    'lang' => 'en'
  ),
),
```
* Add translation files under */assets/config/amalgama*
``` php
//ru.php
<?php

return array(
  'Hello World!' => 'Привет мир!',
  'Hello <?>!' => 'Привет <?>!'
);
```
Usage
--------------------
``` php
// view example
<div><?php $__('Hello World!'); ?></div>
<div><?php $__('Hello <?>!', array($user->name); ?></div>
```
``` php
// lang switcher example
<?php foreach($this->helper->getLangList() as $lang) : ?>
  <?php if ($lang == $this->helper->getCurrentLang()) : ?>
    <span><?php echo $lang; ?></span>
  <?php else: ?>
    <a href="<?php echo $this->helper->langSwitchLink($lang); ?>"><?php echo $lang; ?></a>
  <?php endif; ?>
<?php endforeach; ?>
```
``` php
// Paginate example
...
$page = $this->request->param('page');

$comments = $this->pixie->orm->get('comment');
$pager = $this->pixie->paginate->orm($comments, $page, 10);
$pager->set_url_route('comments', array('lang' => $this->lang));
...
```
``` php
// Validate example
$validator->field('username')
  ->rule('filled')
  ->error($this->__('Field <?> must not be empty', array($this->__('username'))));
```
