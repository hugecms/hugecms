<?php

use think\migration\db\Column;
use think\migration\Migrator;

class CreateContentTables extends Migrator
{
    /**
     * 内容域：栏目、文章、标签、评论、单页
     */
    public function change()
    {
        // 栏目表：树形
        $categories = $this->table('content_categories', ['comment' => '栏目表']);
        $categories->addColumn(Column::unsignedInteger('parent_id')->setNull(false)->setDefault(0)->setComment('父级ID,0=顶级'))
            ->addColumn(Column::string('name', 50)->setNull(false)->setComment('栏目名称'))
            ->addColumn(Column::string('slug', 50)->setNull(false)->setComment('栏目标识,用于URL'))
            ->addColumn(Column::string('description', 255)->setNullable()->setComment('栏目描述'))
            ->addColumn(Column::string('seo_title', 100)->setNullable()->setComment('SEO标题'))
            ->addColumn(Column::string('seo_keywords', 255)->setNullable()->setComment('SEO关键词'))
            ->addColumn(Column::string('seo_description', 255)->setNullable()->setComment('SEO描述'))
            ->addColumn(Column::integer('sort')->setNull(false)->setDefault(0)->setComment('排序'))
            ->addColumn(Column::tinyInteger('status')->setNull(false)->setDefault(1)->setComment('状态:0=隐藏,1=显示'))
            ->addTimestamps()
            ->addIndex('slug', ['unique' => true, 'name' => 'uk_categories_slug'])
            ->addIndex('parent_id', ['name' => 'idx_categories_parent_id'])
            ->create();

        // 文章主表：正文分离到 article_content，列表查询不拖大字段
        $articles = $this->table('content_articles', ['comment' => '文章表']);
        $articles->addColumn(Column::unsignedInteger('category_id')->setNull(false)->setComment('栏目ID'))
            ->addColumn(Column::string('title', 200)->setNull(false)->setComment('标题'))
            ->addColumn(Column::string('slug', 200)->setNullable()->setComment('文章标识,用于URL'))
            ->addColumn(Column::string('summary', 500)->setNullable()->setComment('摘要'))
            ->addColumn(Column::string('cover', 255)->setNullable()->setComment('封面图'))
            ->addColumn(Column::unsignedInteger('author_id')->setNullable()->setComment('作者管理员ID'))
            ->addColumn(Column::string('author', 50)->setNullable()->setComment('作者名称快照'))
            ->addColumn(Column::tinyInteger('is_top')->setNull(false)->setDefault(0)->setComment('置顶:0=否,1=是'))
            ->addColumn(Column::tinyInteger('is_recommend')->setNull(false)->setDefault(0)->setComment('推荐:0=否,1=是'))
            ->addColumn(Column::tinyInteger('status')->setNull(false)->setDefault(0)->setComment('状态:0=草稿,1=发布,2=下架'))
            ->addColumn(Column::unsignedInteger('views')->setNull(false)->setDefault(0)->setComment('浏览量'))
            ->addColumn(Column::dateTime('published_at')->setNullable()->setComment('发布时间'))
            ->addTimestamps()
            ->addSoftDelete()
            ->addIndex('slug', ['unique' => true, 'name' => 'uk_articles_slug'])
            ->addIndex(['category_id', 'status'], ['name' => 'idx_articles_category_status'])
            ->addIndex('published_at', ['name' => 'idx_articles_published_at'])
            ->create();

        // 文章正文表：与文章一对一
        $articleContent = $this->table('content_article_contents', ['id' => false, 'primary_key' => ['article_id'], 'comment' => '文章正文表']);
        $articleContent->addColumn(Column::unsignedInteger('article_id')->setNull(false)->setComment('文章ID'))
            ->addColumn(Column::longText('content')->setNull(false)->setComment('正文(富文本)'))
            ->addTimestamps()
            ->create();

        // 标签表
        $tags = $this->table('content_tags', ['comment' => '标签表']);
        $tags->addColumn(Column::string('name', 50)->setNull(false)->setComment('标签名'))
            ->addTimestamps()
            ->addIndex('name', ['unique' => true, 'name' => 'uk_tags_name'])
            ->create();

        // 文章-标签关联表
        $articleTags = $this->table('content_article_tags', ['id' => false, 'primary_key' => ['article_id', 'tag_id'], 'comment' => '文章标签关联表']);
        $articleTags->addColumn(Column::unsignedInteger('article_id')->setNull(false)->setComment('文章ID'))
            ->addColumn(Column::unsignedInteger('tag_id')->setNull(false)->setComment('标签ID'))
            ->addTimestamps('create_time', null)
            ->addIndex('tag_id', ['name' => 'idx_article_tags_tag_id'])
            ->create();

        // 评论表：多态目标，文章/商品共用，parent_id 支持楼中楼
        $comments = $this->table('content_comments', ['comment' => '评论表']);
        $comments->addColumn(Column::unsignedInteger('user_id')->setNull(false)->setComment('评论用户ID'))
            ->addColumn(Column::string('target_type', 20)->setNull(false)->setComment('目标类型:article,product'))
            ->addColumn(Column::unsignedInteger('target_id')->setNull(false)->setComment('目标ID'))
            ->addColumn(Column::unsignedInteger('parent_id')->setNull(false)->setDefault(0)->setComment('父评论ID,0=顶级'))
            ->addColumn(Column::string('content', 1000)->setNull(false)->setComment('评论内容'))
            ->addColumn(Column::tinyInteger('status')->setNull(false)->setDefault(0)->setComment('状态:0=待审,1=通过,2=拒绝'))
            ->addColumn(Column::string('ip', 45)->setNullable()->setComment('评论IP'))
            ->addTimestamps()
            ->addIndex(['target_type', 'target_id'], ['name' => 'idx_comments_target'])
            ->addIndex('user_id', ['name' => 'idx_comments_user_id'])
            ->create();

        // 单页表：关于我们等固定页面
        $pages = $this->table('content_pages', ['comment' => '单页表']);
        $pages->addColumn(Column::string('title', 100)->setNull(false)->setComment('页面标题'))
            ->addColumn(Column::string('slug', 50)->setNull(false)->setComment('页面标识,如:about'))
            ->addColumn(Column::longText('content')->setNullable()->setComment('页面内容'))
            ->addColumn(Column::string('seo_title', 100)->setNullable()->setComment('SEO标题'))
            ->addColumn(Column::string('seo_keywords', 255)->setNullable()->setComment('SEO关键词'))
            ->addColumn(Column::string('seo_description', 255)->setNullable()->setComment('SEO描述'))
            ->addColumn(Column::tinyInteger('status')->setNull(false)->setDefault(1)->setComment('状态:0=隐藏,1=显示'))
            ->addTimestamps()
            ->addIndex('slug', ['unique' => true, 'name' => 'uk_pages_slug'])
            ->create();
    }
}
