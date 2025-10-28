<?php
namespace Greatis\Login2SeeAdvanced;

use Flarum\Extend;
// use Flarum\User\Event\Activated;
// use Flarum\User\User;
// use Illuminate\Contracts\Bus\Dispatcher;
use Flarum\Api\Serializer\PostSerializer;
use s9e\TextFormatter\Configurator;
use Psr\Log\LoggerInterface;
use Flarum\Settings\SettingsRepositoryInterface;
use Symfony\Contracts\Translation\TranslatorInterface;

class FormatContent
{
    public function __construct()
    {
        $this->settings = resolve(SettingsRepositoryInterface::class);
        $this->translator = resolve(TranslatorInterface::class);
    }
}

class HideContentInPosts extends FormatContent
{
    public function __invoke($serializer, $model, &$attributes)
    {
       $debug=false;
     try
     {
   
       if($debug)
       {
        $logger = resolve(LoggerInterface::class);
       } 
        $user= $serializer->getActor();
        $show_hidden=false;

        if (isset($attributes["contentHtml"])) 
        {

            if (strpos($attributes["contentHtml"], '<login2see>') === false)
            {

                if($debug)
                {
                   $logger->info("No hidden text!",[]);
                } 
                return $attributes;
            }
       } 
       else
       {
                return $attributes;
       }
      //  $logger->info("Attributes:". var_export($attributes, true), ['user_id' => $user->id, 'email' => $user->email]);
        
         if(!$user || $user->isGuest() || !$user->is_email_confirmed)
         {
                if($debug)
                {
                  $logger->info("HideContentInPosts for guests!", ['user_id' => $user->id, 'email' => $user->email]);
                }
         }
         else
         {
            $show_hidden=true;
         }

          if($show_hidden)
          {
               $newHTML = preg_replace('/<login2see>(.*?)<\/login2see>/is', '<div class="login2see"><div class="login2see_title">' . $this->translator->trans('greatis-login2see-advanced.forum.hidden_content') . '</div>$1</div>', $attributes['contentHtml']);
          }
          else
          {
                $newHTML = preg_replace('/<login2see>(.*?)<\/login2see>/is', '<div class="login2see"><div class="login2see_alert">' . $this->translator->trans('greatis-login2see-advanced.forum.login_to_see', array('{login}' => '<a class="login2see_login">' . $this->translator->trans('core.ref.log_in') . '</a>')) . '</div></div>', $attributes['contentHtml']);
          }     
            $attributes['contentHtml'] = $newHTML;

      } catch ( Exception $e ) { }

        return $attributes;
    }

}

return [
    (new Extend\Frontend('forum'))
        ->js(__DIR__.'/js/dist/forum.js')
        ->css(__DIR__.'/resources/less/forum.less'),
    (new Extend\Formatter)
	    ->configure(function (Configurator $config) {
	        $config->BBCodes->addCustom(
	            '[LOGIN]{TEXT}[/LOGIN]',
	            '<login2see>{TEXT}</login2see>'
	        );
   		 }),
    new Extend\Locales(__DIR__ . '/resources/locale'),
    (new Extend\ApiSerializer(PostSerializer::class))
        ->attributes(HideContentInPosts::class),
];
