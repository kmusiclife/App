App (useful bundles for symfony2)
=============

add some bundles in composer.json

``` json
        "liip/imagine-bundle": "dev-master",
        "friendsofsymfony/user-bundle": "dev-master",
        "friendsofsymfony/facebook-bundle": "dev-master"
```

composer.phar update/install 

``` shell
# php composer.phar update 
# php composer.phar install
```

[ [Facebook Developers](https://developers.facebook.com/apps "Facebook Developers") ]
You must register your application on facebook developers site before edit app/config/config.yml because you need couple of keys(App ID, Secret Key). 
Then You must full out facebook dev. forms: "App Domains" and "Website with Facebook Login"

edit app/config/config.yml

``` yaml
# app/config/config.yml

imports:
    - { resource: parameters.yml }
    - { resource: security.yml }

framework:
    #esi:             ~
    translator:      { fallback: %locale% }
    secret:          %secret%
    router:
        resource: "%kernel.root_dir%/config/routing.yml"
        strict_requirements: %kernel.debug%
    form:            true
    csrf_protection: true
    validation:      { enable_annotations: true }
    templating:      { engines: ['twig'] } #assets_version: SomeVersionScheme
    default_locale:  %locale%
    trust_proxy_headers: false # Should Request object should trust proxy headers (X_FORWARDED_FOR/HTTP_CLIENT_IP)
    session:         ~

# Twig Configuration
twig:
    debug:            %kernel.debug%
    strict_variables: %kernel.debug%

# Assetic Configuration
assetic:
    debug:          %kernel.debug%
    use_controller: false
    bundles:        [ AppUploadBundle ]
    #java: /usr/bin/java
    filters:
        cssrewrite: ~
        #closure:
        #    jar: %kernel.root_dir%/Resources/java/compiler.jar
        #yui_css:
        #    jar: %kernel.root_dir%/Resources/java/yuicompressor-2.4.7.jar

# Doctrine Configuration
doctrine:
    dbal:
        driver:   %database_driver%
        host:     %database_host%
        port:     %database_port%
        dbname:   %database_name%
        user:     %database_user%
        password: %database_password%
        charset:  UTF8
    orm:
        auto_generate_proxy_classes: %kernel.debug%
        auto_mapping: true

# Swiftmailer Configuration                                        
swiftmailer:                                                       
    transport: %mailer_transport%                                  
    host:      %mailer_host%                                       
    username:  %mailer_user%                                       
    password:  %mailer_password%                                   
    spool:     { type: memory }                                    

fos_user:                                                          
    db_driver: orm                                                 
    firewall_name: public                                          
    user_class: App\UserBundle\Entity\User                         

    from_email:                                                    
        address: noreply@localhost.com                             
        sender_name: Symfony2 AppBundle Admin                      

    registration:                                                  
        form:                                                      
            type: app_user_registration                            
            handler: app_user.form.handler.registration            
        confirmation:                                              
            enabled: false                                         

    profile:                                                       
        form:                                                      
            type: app_user_profile                                 
            handler: app_user.form.handler.profile                 

    change_password:                                               
        form:                                                      
            type: app_user_change_password                         
            handler: app_user.form.handler.change_password         

    resetting:                                                     
        email:                                                     
            from_email:                                            
                address: noreply@localhost.com                     
                sender_name: Symfony2 AppBundle Admin              

fos_facebook:                                                      
    file:   %kernel.root_dir%/../vendor/facebook/php-sdk/src/base_facebook.php   
    alias:  facebook                                               
    app_id: 111111111111111
    secret: XXXXXXXXXXXXXXXXXXXXXXXXXXXXXXX
    cookie: true                                                   
    permissions: [email, user_birthday, user_location]

services:                                                          

    my.facebook.user:                                              
        class: App\UserBundle\Security\User\Provider\FacebookProvider
        arguments:                                                 
            facebook: "@fos_facebook.api"                          
            userManager: "@fos_user.user_manager"                  
            validator: "@validator"                                
            container: "@service_container"                        

    auth_success_handler:                                          
        class: App\UserBundle\Handler\AuthenticationHandler        
        arguments:                                                 
            Router: "@router"                                      
            SecurityContext: "@security.context"                   
            userManager: "@fos_user.user_manager"                  
            container: "@service_container"                        
            facebook: "@fos_facebook.api"                          

    logout_success_handler:                                        
        class: App\UserBundle\Handler\LogoutHandler                
        arguments:                                                 
            facebook: "@fos_facebook.api"                          
            Router: "@router"                                      
            SecurityContext: "@security.context"                   

    app_common.event.listener:
        class: App\CommonBundle\EventListener\ControllerListener
        arguments: ["@service_container"]
        tags:
            - { name: doctrine.event_listener, event: prePersist }
            - { name: doctrine.event_listener, event: preUpdate }
            - { name: doctrine.event_listener, event: postPersist }
            - { name: doctrine.event_listener, event: postUpdate }
            - { name: doctrine.event_listener, event: preRemove }
            - { name: doctrine.event_listener, event: postRemove }
            - { name: doctrine.event_listener, event: postLoad }
    
    app_common.event.locale.listener:
        class: App\CommonBundle\EventListener\LocaleListener
        arguments:  
        tags:
            - { name: kernel.event_listener, event: security.interactive_login, method: setLocaleForAuthenticatedUser }
            - { name: kernel.event_listener, event: kernel.request, method: setLocaleForUnauthenticatedUser }
            
    app_common.twig.extension:
        class: App\CommonBundle\Twig\CommonExtension
        tags:
            - { name: twig.extension }
        public: true
    
liip_imagine:
    filter_sets:
        
        thumbnail150px:
            quality: 75
            filters:
                thumbnail: { size: [150, 150], mode: inset }

        thumbnail250px:
            quality: 75
            filters:
                thumbnail: { size: [250, 250], mode: inset }

        thumbnail500px:
            quality: 90
            filters:
                thumbnail: { size: [500, 500], mode: inset }

        thumbnail700px:
            quality: 90
            filters:
                thumbnail: { size: [700, 700], mode: inset }

        thumbnail750px:
            quality: 80
            filters:
                thumbnail: { size: [750, 750], mode: inset }
                
        thumbnail80px:
            quality: 75
            filters:
                thumbnail: { size: [80, 80], mode: inset }

        square150px:
            quality: 90
            filters:
                thumbnail: { size: [150, 150], mode: outbound }

        square25px:
            quality: 50
            filters:
                thumbnail: { size: [25, 25], mode: outbound }
        square50px:
            quality: 50
            filters:
                thumbnail: { size: [50, 50], mode: outbound }

```

edit app/config/security.yml

``` yaml

# app/config/security.yml
security:
    providers:
        fos_userbundle:
            id: fos_user.user_provider.username
        my_fos_facebook_provider:
            id: my.facebook.user

    encoders:
        FOS\UserBundle\Model\UserInterface: sha512

    firewalls:
        public:
            pattern: ^/*
            form_login:
                provider: fos_userbundle
                csrf_provider: form.csrf_provider
                success_handler: auth_success_handler

            fos_facebook:
                app_url: "http://www.facebook.com/apps/application.php?id=111111111111111"
                # app_url: "http://example.com/"
                server_url: "http://example.com/"  # you must set it
                check_path: /login_check_facebook
                default_target_path: /
                provider: my_fos_facebook_provider
                success_handler: auth_success_handler

            anonymous: true
            logout:
                success_handler: logout_success_handler

    access_control:
        - { path: ^/login$, role: IS_AUTHENTICATED_ANONYMOUSLY }
        - { path: ^/register, role: IS_AUTHENTICATED_ANONYMOUSLY }
        - { path: ^/resetting, role: IS_AUTHENTICATED_ANONYMOUSLY }
        - { path: ^/login, role: IS_AUTHENTICATED_ANONYMOUSLY }
        - { path: ^/profile/, role: ROLE_USER }
        - { path: ^/upload/, role: ROLE_USER }

    role_hierarchy:
        ROLE_ADMIN:       ROLE_USER
        ROLE_SUPER_ADMIN: ROLE_ADMIN

```

edit app/AppKernel.php

``` php
            new FOS\UserBundle\FOSUserBundle(),
            new FOS\FacebookBundle\FOSFacebookBundle(),
            new Liip\ImagineBundle\LiipImagineBundle(),
            new App\UserBundle\AppUserBundle(),
            new App\UploadBundle\AppUploadBundle(),
            new App\CommonBundle\AppCommonBundle(),
```            

edit app/config/routing.yml

```yaml
_imagine:
    resource: .
    type:     imagine

AppUserBundle:
    resource: "@AppUserBundle/Resources/config/routing.yml"
    prefix:   /

AppUploadBundle:
    resource: "@AppUploadBundle/Resources/config/routing.yml"
    prefix:   /

```

schema update & change the permission for uploads and media directories

```
# php app/console doctrine:schema:update --force
# mkdir web/uploads
# mkdir web/media
# chmod 777 web/uploads
# chmod 777 web/media
# php app/console assets:install
# php app/console assetic:dump
```
