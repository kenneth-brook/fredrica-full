<?php

return [
	'name' => 'Blog',
	'author' => 'Atlantis CMS',
	'version' => '2.1.4',
	'adminURL' => 'admin/modules/blog',
	/**
	 * ex. icon icon-Files
	 * http://docteur-abrar.com/wp-content/themes/thunder/admin/stroke-gap-icons/index.html
	 *
	 * ex. fa fa-beer
	 * http://fontawesome.io/icons/
	 */
	'icon' => 'icon icon-Blog',
	'path' => 'atlantis/blog/src',
	'seedNamespace' => 'Module\Blog\Seed',
	'moduleNamespace' => 'Module\Blog',
	'seeder' => '\Module\Blog\Seed\BlogSeeder',
	'provider' => 'Module\Blog\Providers\BlogServiceProvider',
	'migration' => 'modules/atlantis/blog/src/Module/Blog/Migrations/',
	'extra' => NULL,
	'description' => 'The Atlantis Blog Module.',

	'create_pages' => [
		[
			'name' => 'Blog List',
			'url' => 'blog',
			'body' => '<div data-pattern-func="module:blog@all">All</div>',
		],
		[
			'name' => 'Blog Post',
			'url' => 'blog/:any',
			'body' => '<div data-pattern-func="module:blog@single">single blog post</div><div data-pattern-func="module:blog@quicklinks">blog links</div>',
		],
		[
			'name' => 'Blog Archive',
			'url' => 'blog-archive/:any',
			'body' => '<div data-pattern-func="module:blog@archivePosts">Archive posts</div>',
		],
		[
			'name' => 'Blog Category',
			'url' => 'blog-category/:any',
			'body' => '<div data-pattern-func="module:blog@showPostsByCategory">Blog category</div>',
		],
	],
	'create_patterns' => [
		[
			'name' => 'Blog Archives list Widget',
			'language' => 'en',
			'status' => '1',
			'mask' => 'none',
			'url' => 'NULL',
		],
		[
			'name' => 'Blog Categories list Widget',
			'language' => 'en',
			'status' => '1',
			'mask' => 'none',
			'url' => 'NULL',
		],
		[
			'name' => 'Recent short blog posts Widget',
			'language' => 'en',
			'status' => '1',
			'mask' => 'none',
			'url' => 'NULL',
		],
	],
];
