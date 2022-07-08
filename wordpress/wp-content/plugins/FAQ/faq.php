<?php

/*
 * Plugin Name: FAQ
 * Description: Add and Delete the casino filters shown at the home page
 * Version: 1.0
 * Author: Diamadis Giorgos
 */

class FAQ
{
    public function __construct()
    {
        add_action("admin_menu", array($this, "adminPage"));
        $this->createTable();

    }

    function createTable()
    {
        global $wpdb;
        $charset_collate = $wpdb->get_charset_collate();
        $table_name = $wpdb->prefix . 'faq';
        $sql = "CREATE TABLE `$table_name` (
              `id` int(11) NOT NULL AUTO_INCREMENT,
              `question` varchar(220) DEFAULT NULL,
              `answer` TEXT default null,
              `post_id` int(11) NOT NULL,
              PRIMARY KEY(id),
              FOREIGN KEY (post_id) references wp_posts(ID)
              ) ENGINE=MyISAM DEFAULT CHARSET=latin1;
              ";
        if ($wpdb->get_var("SHOW TABLES LIKE '$table_name'") != $table_name) {
            require_once(ABSPATH . 'wp-admin/includes/upgrade.php');
            dbDelta($sql);
        }
    }

    function adminPage()
    {
        add_menu_page("FAQ Manager", "FAQ Manager", "manage_options", __FILE__, array($this, "HTML"));
    }

    function HTML()
    {
        global $wpdb;
        $table_name = $wpdb->prefix . 'faq';
        if ($_POST['add_new']) {
            $question = $_POST["question"];
            $answer = $_POST["answer"];
            $post_id = $_POST["post_id"];

            $wpdb->query("insert into $table_name(question,answer,post_id) values('$question','$answer',$post_id)");
        }else if ($_POST['delete']){
            $id = $_POST['faq_id'];
            $wpdb->query("delete from $table_name where id=$id");
        }else if ($_POST['edit']){
            $id = $_POST['faq_id'];
            $question = $_POST["question"];
            $answer = $_POST["answer"];
            $wpdb->query("update $table_name set question='$question',answer='$answer' where id=$id ");
        }


        ?>
        <div class="diamadis">
        <div class="container">
            <form method="post" action="">
                <div class="row">
                    <div class="input-field col s12">
                        <input id="question" name="search_id" type="text" class="validate">
                        <label for="first_name">Enter Post Id</label>
                    </div>
                </div>

                <button class="btn waves-effect waves-light" type="submit" name="action">Submit
                </button>

            </form>
        </div>
        </div>
        <?php

        if (isset($_POST['search_id']) || isset($_POST['post_id'])) {
            $id = $_POST['search_id'] ?? $_POST['post_id'];

            $faqs = $wpdb->get_results("select * from $table_name
                                                where post_id=$id");

            $post = $wpdb->get_results("select * from wp_posts
                                                where ID=$id");

            if (count($post)!==0){
                print ''?>
                <div class="diamadis">
                 <hr>
            <h3 style="text-align: center"><?=$post[0]->post_title?></h3>
            <div class="container">

            <div class="row">
                <form class="col s12" method="post">
                    <input type="hidden" name="add_new" value="1">
                    <input type="hidden" value="<?= $id ?>" name="post_id">
                    <div class="row">
                        <div class="input-field col s12">
                            <input id="question" type="text" name="question" class="validate">
                            <label for="first_name">Question</label>
                        </div>
                    </div>
                    <div class="row">
                        <div class="input-field col s12">
                            <textarea id="textarea1" name="answer" class="materialize-textarea"></textarea>
                            <label for="textarea1">Answer</label>
                        </div>
                    </div>

                    <button class="btn waves-effect waves-light" type="submit" name="action">Add new
                    </button>
                </form>
            </div>

                <?php
                print " <div class='row'>";
            foreach ($faqs as $faq) {
                print ''?>
                <div class='col s6 m6'>
                    <form method="post" action=''>
                        <div class='card z-depth-5 darken-1'>
                            <div class='row'>
                                <div class='input-field col s12'>
                                    <input id='question' value='<?=$faq->question?>' type='text' name='question' class='validate'>
                                    <label for='first_name'>Question</label>
                                </div>
                            </div>
                            <div class='row'>
                                <div class='input-field col s12'>
                                    <textarea id='textarea1' name='answer' class='materialize-textarea'><?=$faq->answer?></textarea>
                                    <label for='textarea1'>Answer</label>
                                </div>
                            </div>
                            <input type="hidden" value="<?= $faq->id?>" name="faq_id">
                            <input type="hidden" value="<?= $faq->post_id?>" name="post_id">
                            <button class='btn waves-effect waves-light' type='submit' value="1" name='edit'>Edit</button>
                            <button class='btn waves-effect waves-light' type='submit' value="1"  name='delete'>Delete</button>
                        </div>
                    </form>
                </div>
                <?php
            }
            print " </div>";

            print "</div>";
            print "</div>";
            }else{
            }

        }
        
    }
}

new FAQ();