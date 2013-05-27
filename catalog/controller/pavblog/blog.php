<?php 
/******************************************************
 * @package Pav blog module for Opencart 1.5.x
 * @version 1.0
 * @author http://www.pavothemes.com
 * @copyright	Copyright (C) Feb 2013 PavoThemes.com <@emai:pavothemes@gmail.com>.All rights reserved.
 * @license		GNU General Public License version 2
*******************************************************/

/**
 * class ControllerpavblogBlog 
 */
	class ControllerpavblogBlog extends Controller {
		private $mparams = '';
		public function preload(){
			$this->language->load('module/pavblog');
		
			$this->load->model("pavblog/blog");
			$this->load->model("pavblog/comment");
			$mparams = $this->config->get( 'pavblog' );
			$config = new Config();
			if( $mparams ){
				foreach( $mparams as $key => $value ){
					$config->set( $key, $value );
				}
			}
			$this->mparams = $config; 
			
	
			if( !defined("_PAVBLOG_MEDIA_") ){
				if (file_exists('catalog/view/theme/' . $this->config->get('config_template') . '/stylesheet/pavblog.css')) {
					$this->document->addStyle('catalog/view/theme/' . $this->config->get('config_template') . '/stylesheet/pavblog.css');
				} else {
					$this->document->addStyle('catalog/view/theme/default/stylesheet/pavblog.css');
				}
				define("_PAVBLOG_MEDIA_",true);
			}
		}
		
		public function getParam( $key, $value='' ){
			return  $this->mparams->get( $key, $value );
		}
		
		/**
		 * get module object
		 *
		 */
		public function getModel( $model='blog' ){
			return $this->{"model_pavblog_{$model}"};
		}
		
		/**
		 *
	     * index action
		 */
		public function index() {  
		
			$this->preload();
			
			$this->load->model('tool/image'); 
			
			
			$this->data['breadcrumbs'] = array();
			
			$this->data['breadcrumbs'][] = array(
				'text'      => $this->language->get('text_home'),
				'href'      => $this->url->link('common/home'),
				'separator' => false
			);
		 
			
			
		

			$blog_id = $this->request->get['id'];
			$blog = $this->getModel()->getInfo( $blog_id );
			$this->load->model('pavblog/category');
			
			$users = $this->model_pavblog_category->getUsers();
			$this->data['config']	 = $this->mparams; 
			if ($blog) {
			
				$category_id = $blog['category_id'];
				$title = $blog['meta_title'] ? $blog['meta_title']:$blog['title']; 
				$this->document->setTitle( $title ); 
				$this->document->setDescription( $blog['meta_description'] );
				$this->document->setKeywords( $blog['meta_keyword'] );
				
				$this->data['breadcrumbs'][] = array(
					'text'      => $blog['category_title'],
					'href'      => $this->url->link('pavblog/category', 'id=' .  $category_id),      		
					'separator' => $this->language->get('text_separator')
				);	
				$this->data['breadcrumbs'][] = array(
					'text'      => $blog['title'],
					'href'      => $this->url->link('pavblog/blog', 'id=' .  $blog_id),      		
					'separator' => $this->language->get('text_separator')
				);		
							
				$this->data['heading_title'] = $blog['title'];

        $this->data['text_wait'] = $this->language->get('text_wait');				
        $this->data['entry_captcha'] = $this->language->get('entry_captcha');
        $this->data['entry_captcha'] = $this->language->get('entry_captcha');		
				$this->data['button_continue'] = $this->language->get('button_continue');
				
				$this->data['description'] = html_entity_decode($blog['description'], ENT_QUOTES, 'UTF-8');
				$this->data['content'] = html_entity_decode($blog['content'], ENT_QUOTES, 'UTF-8');
				
				$this->data['continue'] = $this->url->link('common/home');
				$type = array('l'=>'thumb_large','s'=>'thumb_small');
				$imageType = isset($type[$this->mparams->get('blog_image_type')])?$type[$this->mparams->get('blog_image_type')]:'thumb_xsmall';
				
				if( $blog['image'] ){	
					$blog['thumb_large'] = $this->model_tool_image->resize($blog['image'], $this->mparams->get('general_lwidth'), $this->mparams->get('general_lheight'),'w');
					$blog['thumb_small'] = $this->model_tool_image->resize($blog['image'], $this->mparams->get('general_swidth'), $this->mparams->get('general_sheight'),'w' );
					$blog['thumb_xsmall'] = $this->model_tool_image->resize($blog['image'],$this->mparams->get('general_xwidth'), $this->mparams->get('general_xheight') ,'w');
				}else {
					$blog['thumb_large'] = '';
					$blog['thumb_small'] = '';
					$blog['thumb_xsmall'] = '';
				}
				
				$blog['thumb'] = $blog[$imageType];
				
				$blog['description'] = html_entity_decode( $blog['description'] );
				$blog['author'] = isset($users[$blog['user_id']])?$users[$blog['user_id']]:$this->language->get('text_none_author');
				$blog['category_link'] =  $this->url->link( 'pavblog/category', "id=".$blog['category_id'] );
				$blog['comment_count'] =  $this->getModel('comment')->countComment( $blog['blog_id'] );
				$blog['link'] =  $this->url->link( 'pavblog/blog','id='.$blog['blog_id'] );
				
				
				$this->data['comment_action'] = $this->url->link( 'pavblog/blog/comment','id='.$blog['blog_id'] );

				
				$this->data['blog'] = $blog;
				$this->data['samecategory'] = $this->getModel()->getSameCategory( $blog['category_id'], $blog['blog_id'] );
				$this->data['social_share'] =  '';
				$data = array(
					'filter_category_id' => '',
					'filter_tag'		=> $blog['tags'],
					'not_in'           => $blog['blog_id'],
					'sort'               => 'created',
					'order'              => 'DESC',
					'start'              => 0,
					'limit'              => 10
				);

				$related = $this->getModel('blog')->getListBlogs(  $data );
			
				
				$this->data['related'] = $related;
				
				$ttags = explode( ",",$blog['tags']);
				$tags  = array();
				
				foreach( $ttags as $tag ){
					$tags[trim($tag)] = $this->url->link( 'pavblog/blogs','tag='.trim($tag) );
				}
				
				
				$this->data['tags'] = $tags;
				
				$this->data['link'] =  $this->url->link( 'pavblog/blog','id='.$blog['blog_id'] );
				
				if (isset($this->request->get['page'])) {
					$page = $this->request->get['page'];
				} else { 
					$page = 1;
				}	
				$limit = $this->getParam( 'comment_limit' );
			
				$url = '';
				$pagination = new Pagination();
				$pagination->total = $blog['comment_count'];
				$pagination->page = $page;
				$pagination->limit =  $limit;
				$pagination->text = $this->language->get('text_pagination');
				$pagination->url = $this->url->link('pavblog/blog', 'id=' . $blog['blog_id'] . $url . '&page={page}');
				$data = array(
					'blog_id' => $blog['blog_id'],
					'start'              => ($page - 1) * $limit,
					'limit'              => $limit
				);
				$this->data['comments'] = $this->getModel('comment')->getList( $data );
				
				$this->data['pagination'] = $pagination->render();
								
				$this->getModel( 'blog' )->updateHits( $blog_id ); 
				if (file_exists(DIR_TEMPLATE . $this->config->get('config_template') . '/template/pavblog/blog.tpl')) {
					$this->template = $this->config->get('config_template') . '/template/pavblog/blog.tpl';
				} else {
					$this->template = 'default/template/pavblog/blog.tpl';
				}
				
				$this->children = array(
					'common/column_left',
					'common/column_right',
					'common/content_top',
					'common/content_bottom',
					'common/footer',
					'common/header'
				);
							
				$this->response->setOutput($this->render());
			} else {
				$this->data['breadcrumbs'][] = array(
					'text'      => $this->language->get('text_error'),
					'href'      => $this->url->link('information/information', 'category_id=' . $category_id),
					'separator' => $this->language->get('text_separator')
				);
					
				$this->document->setTitle($this->language->get('text_error'));

				$this->data['heading_title'] = $this->language->get('text_error');

				$this->data['text_error'] = $this->language->get('text_error');

				$this->data['button_continue'] = $this->language->get('button_continue');
				
				$this->data['continue'] = $this->url->link('common/home');

				if (file_exists(DIR_TEMPLATE . $this->config->get('config_template') . '/template/error/not_found.tpl')) {
					$this->template = $this->config->get('config_template') . '/template/error/not_found.tpl';
				} else {
					$this->template = 'default/template/error/not_found.tpl';
				}
				
				$this->children = array(
					'common/column_left',
					'common/column_right',
					'common/content_top',
					'common/content_bottom',
					'common/footer',
					'common/header'
				);
						
				$this->response->setOutput($this->render());
			}
		}
		
		/**
		 * process adding comment
		 */
		public function comment(){
		
		$this->preload();

		$json = array();
		
		if ($this->request->server['REQUEST_METHOD'] == 'POST') {
			if ((utf8_strlen($this->request->post['name']) < 3) || (utf8_strlen($this->request->post['name']) > 25)) {
				$json['error'] = $this->language->get('error_name');
			}
			
			if( !preg_match( "/^([a-zA-Z0-9])+([a-zA-Z0-9\._-])*@([a-zA-Z0-9_-])+([a-zA-Z0-9\._-]+)+$/", $this->request->post['email'])) {
				$json['error'] = $this->language->get('error_email');
      }
      
			if ((utf8_strlen($this->request->post['text']) < 25) || (utf8_strlen($this->request->post['text']) > 1000)) {
				$json['error'] = $this->language->get('error_comment');
			}
	
			if (empty($this->session->data['captcha']) || ($this->session->data['captcha'] != $this->request->post['captcha'])) {
				$json['error'] = $this->language->get('error_captcha');
			}
				
			if (!isset($json['error'])) {
        $data = array(
            'email'   =>  $this->request->post['email'],
            'user'    =>  html_entity_decode($this->request->post['name'], ENT_QUOTES, 'UTF-8'),
            'comment' =>  html_entity_decode($this->request->post['text'], ENT_QUOTES, 'UTF-8'),
            'blog_id' =>  $this->request->post['blog_id']
          );			
          
				$this->getModel('comment')->saveComment( $data, $this->mparams->get('auto_publish_comment') );
				
				$json['success'] = $this->language->get('text_success');
			}
		}
		
		$this->response->setOutput(json_encode($json));

		}
		
		// Captcha
		public function captcha() {
      $this->load->library('captcha');
      $captcha = new Captcha();
      $this->session->data['captcha'] = $captcha->getCode();
      $captcha->showImage();
    }
    
	}	
	?>