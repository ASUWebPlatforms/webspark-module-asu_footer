<?php

namespace Drupal\asu_footer\Plugin\Block;

use Drupal\Core\Block\BlockBase;
use Drupal\Core\Form\FormStateInterface;
use Drupal\Core\Link;
use Drupal\media\Entity\Media;
use Drupal\file\Entity\File;

/**
 * Provides the ASU footer block which deploys the component footer.
 *
 * @Block(
 *   id = "asu_footer",
 *   admin_label = @Translation("ASU footer"),
 *   category = @Translation("ASU footer"),
 * )
 */
class AsuFooterBlock extends BlockBase {

  const ORDINAL_INDEX = ['second', 'third', 'fourth', 'fifth', 'sixth'];

  /**
   * {@inheritdoc}
   */
  public function build() {
    $config = $this->getConfiguration();

    //Default images.
    $module_handler = \Drupal::service('module_handler');
    $path_module = $module_handler->getModule('asu_footer')->getPath();
    $src_unit_logo = base_path() . $path_module . '/img/ASU-EndorsedLogo.png';
    $src_footer_img = base_path() . $path_module . '/img/GlobalFooter-No1InnovationLockup.png';
    $unit_custom_logo = $this->load_unit_logo($config['asu_footer_block_unit_logo']);
    $columns_data = [];
    //Columns data.
    foreach (static::ORDINAL_INDEX as $index) {
      $column_data['title'] = $config['asu_footer_block_' . $index . '_title'];
      $column_data['menu_items'] = $this->get_menu_column($config['asu_footer_block_menu_' . $index . '_column_name']);
      $columns_data[] = $column_data;
    }

    return [
      '#theme' => 'asu_footer__footer_block',
      '#src_unit_logo' => $src_unit_logo,
      '#unit_custom_logo' => $unit_custom_logo,
      '#src_footer_img' => $src_footer_img,
      '#show_logo_social_media' => $config['asu_footer_block_show_logo_social_media'],
      '#facebook_url' => $config['asu_footer_block_facebook_url'],
      '#twitter_url' => $config['asu_footer_block_twitter_url'],
      '#linkedin_url' => $config['asu_footer_block_linkedin_url'],
      '#instagram_ulr' => $config['asu_footer_block_instagram_ulr'],
      '#youtube_url' => $config['asu_footer_block_youtube_url'],
      '#show_columns' => $config['asu_footer_block_show_columns'],
      '#unit_name' => $config['asu_footer_block_unit_name'],
      '#columns_data' => $columns_data,
      '#link_title' => $config['asu_footer_block_link_title'],
      '#link_url' => $config['asu_footer_block_link_url'],
      '#cta_title' => $config['asu_footer_block_cta_title'],
      '#cta_url' => $config['asu_footer_block_cta_url'],
    ];
  }

  /**
   * {@inheritdoc}
   */
  public function blockForm($form, FormStateInterface $form_state) {
    $form = parent::blockForm($form, $form_state);

    // Config for this instance.
    $config = $this->getConfiguration();

    $form['asu_footer_block_show_logo_social_media'] = [
      '#type' => 'checkbox',
      '#title' => $this->t('Show social media and unit logo'),
      '#default_value' => $config['asu_footer_block_show_logo_social_media'],
    ];
    $form['asu_footer_block_unit_logo'] = [
      '#type' => 'media_library',
      '#allowed_bundles' => ['image'],
      '#title' => t('Upload Unit logo'),
      '#default_value' => $config['asu_footer_block_unit_logo'] ?? '',
      '#states' => array(
        'visible' => array(
          ':input[name="settings[asu_footer_block_show_logo_social_media]"]' => array(
            'checked' => TRUE,
          ),
        ),
      ),
    ];
    $form['asu_footer_block_facebook_url'] = [
      '#type' => 'textfield',
      '#title' => $this->t('Facebook Social Media'),
      '#field_prefix' => 'https://www.facebook.com/',
      '#size' => 40,
      '#default_value' => $config['asu_footer_block_facebook_url'] ?? '',
      '#states' => array(
        'visible' => array(
          ':input[name="settings[asu_footer_block_show_logo_social_media]"]' => array(
            'checked' => TRUE,
          ),
        ),
      ),
    ];
    $form['asu_footer_block_twitter_url'] = [
      '#type' => 'textfield',
      '#title' => $this->t('Twitter Social Media'),
      '#field_prefix' => 'https://twitter.com/',
      '#size' => 40,
      '#default_value' => $config['asu_footer_block_twitter_url'] ?? '',
      '#states' => array(
        'visible' => array(
          ':input[name="settings[asu_footer_block_show_logo_social_media]"]' => array(
            'checked' => TRUE,
          ),
        ),
      ),
    ];
    $form['asu_footer_block_linkedin_url'] = [
      '#type' => 'textfield',
      '#title' => $this->t('LinkedIn Social Media'),
      '#field_prefix' => 'https://www.linkedin.com/',
      '#size' => 40,
      '#default_value' => $config['asu_footer_block_linkedin_url'] ?? '',
      '#states' => array(
        'visible' => array(
          ':input[name="settings[asu_footer_block_show_logo_social_media]"]' => array(
            'checked' => TRUE,
          ),
        ),
      ),
    ];
    $form['asu_footer_block_instagram_ulr'] = [
      '#type' => 'textfield',
      '#title' => $this->t('Instagram Social Media'),
      '#field_prefix' => 'https://www.youtube.com/',
      '#size' => 40,
      '#default_value' => $config['asu_footer_block_instagram_ulr'] ?? '',
      '#states' => array(
        'visible' => array(
          ':input[name="settings[asu_footer_block_show_logo_social_media]"]' => array(
            'checked' => TRUE,
          ),
        ),
      ),
    ];
    $form['asu_footer_block_youtube_url'] = [
      '#type' => 'textfield',
      '#title' => $this->t('YouTube Social Media'),
      '#field_prefix' => 'https://www.instagram.com/',
      '#size' => 40,
      '#default_value' => $config['asu_footer_block_youtube_url'] ?? '',
      '#states' => array(
        'visible' => array(
          ':input[name="settings[asu_footer_block_show_logo_social_media]"]' => array(
            'checked' => TRUE,
          ),
        ),
      ),
    ];

    $form['asu_footer_block_show_columns'] = [
      '#type' => 'checkbox',
      '#title' => $this->t('Show columns'),
      '#default_value' => $config['asu_footer_block_show_columns'],
    ];
    $form['asu_footer_block_unit_name'] = [
      '#type' => 'textfield',
      '#title' => $this->t('Name of Unit/School/College'),
      '#description' => $this->t('Site title to appear in the header.'),
      '#default_value' => $config['asu_footer_block_unit_name'] ?? '',
      '#states' => array(
        'visible' => array(
          ':input[name="settings[asu_footer_block_show_columns]"]' => array(
            'checked' => TRUE,
          ),
        ),
        'required' => array(
          ':input[name="settings[asu_footer_block_show_columns]"]' => array(
            'checked' => TRUE,
          ),
        ),
      ),
    ];
    $form['asu_footer_block_link'] = array(
      '#type' => 'details',
      '#title' => t('Link'),
      '#open' => TRUE,
      '#states' => array(
        'visible' => array(
          ':input[name="settings[asu_footer_block_show_columns]"]' => array(
            'checked' => TRUE,
          ),
        ),
      ),
    );

    $form['asu_footer_block_link']['asu_footer_block_link_title'] = array(
      '#type' => 'textfield',
      '#title'  => t('Link Title'),
      '#default_value' => $config['asu_footer_block_link_title'] ?? '',
      '#maxlength' => 60,
      '#states' => array(
        'visible' => array(
          ':input[name="settings[asu_footer_block_show_columns]"]' => array(
            'checked' => TRUE,
          ),
        ),
      ),
    );

    $form['asu_footer_block_link']['asu_footer_block_link_url'] = array(
      '#type' => 'textfield',
      '#title'  => t('URL'),
      '#default_value' => $config['asu_footer_block_link_url'] ?? '',
      '#maxlength' => 60,
      '#states' => array(
        'visible' => array(
          ':input[name="settings[asu_footer_block_show_columns]"]' => array(
            'checked' => TRUE,
          ),
        ),
      ),
    );
    $form['asu_footer_block_cta'] = array(
      '#type' => 'details',
      '#title' => t('CTA'),
      '#open' => TRUE,
      '#states' => array(
        'visible' => array(
          ':input[name="settings[asu_footer_block_show_columns]"]' => array(
            'checked' => TRUE,
          ),
        ),
      ),
    );

    $form['asu_footer_block_cta']['asu_footer_block_cta_title'] = array(
      '#type' => 'textfield',
      '#title'  => t('CTA Title'),
      '#default_value' => $config['asu_footer_block_cta_title'] ?? '',
      '#maxlength' => 60,
      '#states' => array(
        'visible' => array(
          ':input[name="settings[asu_footer_block_show_columns]"]' => array(
            'checked' => TRUE,
          ),
        ),
      ),
    );

    $form['asu_footer_block_cta']['asu_footer_block_cta_url'] = array(
      '#type' => 'textfield',
      '#title'  => t('URL'),
      '#default_value' => $config['asu_footer_block_cta_url'] ?? '',
      '#maxlength' => 60,
      '#states' => array(
        'visible' => array(
          ':input[name="settings[asu_footer_block_show_columns]"]' => array(
            'checked' => TRUE,
          ),
        ),
      ),
    );

    // Get system menu options.
    $menu_options = menu_ui_get_menus();
    foreach (static::ORDINAL_INDEX as $index) {

      $form[$index . '_column'] = array(
        '#type' => 'details',
        '#title' => $this->t($index . ' Column menu'),
        '#open' => FALSE,
        '#states' => array(
          'visible' => array(
            ':input[name="settings[asu_footer_block_show_columns]"]' => array(
              'checked' => TRUE,
            ),
          ),
        ),
      );
      $form[$index . '_column']['asu_footer_block_'. $index . '_title'] = [
        '#type' => 'textfield',
        '#title' => $this->t('Title'),
        '#default_value' => $config['asu_footer_block_' . $index . '_title'] ?? ''
      ];
      $form[$index . '_column']['asu_footer_block_menu_' . $index . '_column_name'] = [
        '#type' => 'select',
        '#title' => $this->t('Menu to insert in ' . $index . ' column'),
        '#description' => $this->t('Select the menu to insert.'),
        '#options' => $menu_options,
        '#empty_option' => t('- None -'),
        '#empty_value' => '_none',
        '#default_value' => $config['asu_footer_block_menu_' . $index . '_column_name'] ?? '',
        '#states' => array(
          'visible' => array(
            ':input[name="settings[asu_footer_block_show_columns]"]' => array(
              'checked' => TRUE,
            ),
          ),
        ),
      ];
    }

    return $form;
  }

  /**
   * {@inheritdoc}
   */
  public function blockSubmit($form, FormStateInterface $form_state) {
    parent::blockSubmit($form, $form_state);

    $values = $form_state->getValues();
    $this->configuration['asu_footer_block_unit_logo'] =
      $values['asu_footer_block_unit_logo'];
    $this->configuration['asu_footer_block_unit_name'] =
      $values['asu_footer_block_unit_name'];
    $this->configuration['asu_footer_block_show_logo_social_media'] =
      $values['asu_footer_block_show_logo_social_media'];
    $this->configuration['asu_footer_block_facebook_url'] =
      $values['asu_footer_block_facebook_url'];
    $this->configuration['asu_footer_block_twitter_url'] =
      $values['asu_footer_block_twitter_url'];
    $this->configuration['asu_footer_block_linkedin_url'] =
      $values['asu_footer_block_linkedin_url'];
    $this->configuration['asu_footer_block_instagram_ulr'] =
      $values['asu_footer_block_instagram_ulr'];
    $this->configuration['asu_footer_block_youtube_url'] =
      $values['asu_footer_block_youtube_url'];
    $this->configuration['asu_footer_block_show_columns'] =
      $values['asu_footer_block_show_columns'];
    $this->configuration['asu_footer_block_link_title'] =
      $values['asu_footer_block_link']['asu_footer_block_link_title'];
    $this->configuration['asu_footer_block_link_url'] =
     $values['asu_footer_block_link']['asu_footer_block_link_url'];
    $this->configuration['asu_footer_block_cta_title'] =
      $values['asu_footer_block_cta']['asu_footer_block_cta_title'];
    $this->configuration['asu_footer_block_cta_url'] =
     $values['asu_footer_block_cta']['asu_footer_block_cta_url'];

    foreach (static::ORDINAL_INDEX as $index) {
      $this->configuration['asu_footer_block_' . $index . '_title'] =
        $values[$index . '_column']['asu_footer_block_' . $index . '_title'];
      $this->configuration['asu_footer_block_menu_' . $index . '_column_name'] =
        $values[$index . '_column']['asu_footer_block_menu_' . $index . '_column_name'];
    }
  }

  function get_menu_column($menu_name) {
    // Get customer care top level menu.
    $menu_tree = \Drupal::menuTree();
    $parameters = $menu_tree->getCurrentRouteMenuTreeParameters($menu_name);
    $parameters->setMinDepth(0);
    $parameters->onlyEnabledLinks();

    $tree = $menu_tree->load($menu_name, $parameters);
    $manipulators = [
      ['callable' => 'menu.default_tree_manipulators:checkAccess'],
      ['callable' => 'menu.default_tree_manipulators:generateIndexAndSort'],
    ];
    $tree = $menu_tree->transform($tree, $manipulators);

    $menu_items = [];
    // Add menu items to array list.
    foreach ($tree as $item) {
      $title = $item->link->getTitle();
      $url = $item->link->getUrlObject();
      $link = Link::fromTextAndUrl($title, $url)->toRenderable();
      $link['#attributes'] = ['class' => 'nav-link'];
      $item = [
        '#markup' => render($link),
      ];
      $menu_items[] = $item;
    }

    return $menu_items;
  }

  function load_unit_logo($mid) {
    if ($mid) {
      $media = Media::load($mid);
      $fid = $media->field_media_image->target_id;
      $file = File::load($fid);
      // Load main_image
      if ($file) {
        $variables = array(
          'responsive_image_style_id' => 'unit_logo',
          'uri' => $file->getFileUri(),
        );
        // The image.factory service will check if our image is valid.
        $image = \Drupal::service('image.factory')->get($file->getFileUri());
        if ($image->isValid()) {
          $variables['width'] = $image->getWidth();
          $variables['height'] = $image->getHeight();
        }
        else {
          $variables['width'] = $variables['height'] = NULL;
        }
        $logo_build = [
          '#theme' => 'responsive_image',
          '#width' => $variables['width'],
          '#height' => $variables['height'],
          '#responsive_image_style_id' => $variables['responsive_image_style_id'],
          '#uri' => $variables['uri'],
        ];
        // Add the file entity to the cache dependencies.
        // This will clear our cache when this entity updates.
        $renderer = \Drupal::service('renderer');
        $renderer->addCacheableDependency($logo_build, $file);

        // Return the render array as block content.
        return $logo_build;
      }
    }
    return NULL;
  }
}
