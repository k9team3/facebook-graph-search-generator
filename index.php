<?php
require_once('model.php');
$FbGSG = new FbGSG();
$cookie_data = $FbGSG->get_cookie_data();
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <title>Facebook Graph Search Generator</title>

    <link rel="stylesheet" type="text/css" href="style.css?v=1.1" />
    <script type="text/javascript" src="js/jquery-2.2.3.min.js"></script>
</head>
<body>

    <main>

        <h1>Facebook Graph Search Generator</h1>

        <!-- GETTING STARTED -->
        <section>
            <h2>1. Getting started</h2>
            <p>
                <strong>What is Facebook Graph Search, and why does this page exist?</strong> <br />
                Facebook Graph Search is a powerful search engine that lets everyone play around with Facebooks
                "big data". It only shows you what is already accessible to you (at least that's what Facebook claims),
                but in a strctured and simple way that has been met by a lot of concerns about privacy.
                Especially since it can reveal things you might not think you had access to.
                This generator is simply just to give you some insight in what it's all about and some tips on the
                possibilities.
                <a target="_blank" href="https://en.wikipedia.org/wiki/Facebook_Graph_Search">Read more on Wikipedia.</a>
            </p>
            <p>
                <strong>Set the lanuage of your Facebook to English (US)</strong> <br />
                At the moment, Facebook Graph Search is only available for United States with English (US) language
                selected. However, anyone can change to English (US) by going to "Settings" -> "Language" in the upper
                right corner on Facebook. And search results will still show up from the entire Facebook database!
            </p>
            <p>
                <strong>Why not just type names directly in the search bar?</strong> <br />
                Well, a lot of stuff can have the same name. Everything on Facebook (people, places, events, etc.)
                have a unique ID, a so called UID. This is simply just a number, but very important to get the
                search accurate. And to get the UID for a person, use the persons unique username from the URL.
            </p>
        </section>

        <!-- ADD & LIST ITEMS -->
        <section>
            <h2>2. Add people by username</h2>
            <p class="input-wrap">
                <input type="text" id="input-username-or-uid" name="username-or-uid" placeholder="Username (required)">
                <input type="text" id="input-friendlyname" name="friendlyname" placeholder="Real name (optional)">
                <span id="btn-add-item" class="button">Add</span>
                <img id="loading-add-item" class="loading" src="img/loading.gif" width="24" />
            </p>
            <p class="example">
                <em>
                <strong>Example:</strong>
                    Go to a Facebook profile and look in the address bar and find something like: <br />
                    "https://www.facebook.com/JohnDoe". The username is simply "JohnDoe".
                    The friendly name can be whatever you want.
                </em>
            </p>

            <table id="fb-items" cellpadding="0" cellspacing="2">
                <tr>
                    <th>Name</th>
                    <th>Facebook UID</th>
                    <th>Action</th>
                </tr>
            </table>
        </section>

        <!-- SEARCH GENERATED -->
        <section>
            <h2>3. Search</h2>

            <!-- PERSON-->
            <h3>Single person</h3>
            <p class="search input-wrap">
                <select onchange="generate_url_personal();" id="select-personal-what">
                    <option value="stories">Posts with</option>
                    <option value="stories-by">Posts by</option>
                    <option value="stories-commented">Posts commented by</option>
                    <option value="stories-tagged">Posts with tag of</option>
                    <option value="photos-liked">Photos liked by</option>
                    <option value="photos-of">Photos made of</option>
                    <option value="photos-tagged">Photos with tag of</option>
                    <option value="photos-commented">Photos commented by</option>
                    <option value="events">Events invitations for</option>
                    <option value="events-joined">Events joined by</option>
                    <option value="groups">Groups joined by</option>
                    <option value="places-liked">Places liked by</option>
                    <option value="places-visited">Places visited by</option>
                    <option value="pages-liked">Pages liked by</option>
                    <option value="apps-used">Apps used by</option>
                    <option value="videos">Videos with</option>
                    <option value="videos-by">Videos by</option>
                    <option value="videos-liked">Videos liked by</option>
                    <option value="videos-commented">Videos commented by</option>
                    <option value="friends">Friends of</option>
                    <option value="relatives">Relatives of</option>
                </select>
                <select onchange="generate_url_personal();" id="select-personal-with-who">
                    <option value="0">(optional)</option>
                    <option value="friends/">friends of</option>
                </select>
                <select onchange="generate_url_personal();" id="select-personal-who" class="select-item">
                    <option value="0">-- Choose who</option>
                </select>
                <a href="#" target="_blank" id="btn-search-personal" class="button">Search</a>
            </p>

            <!-- COMMON -->
            <h3>What do they have in common?</h3>
            <p class="search input-wrap">
                <select onchange="generate_url_common();" id="select-common-what">
                    <option value="places-visited">Places visited by</option>
                    <option value="pages-liked">Pages liked by</option>
                    <option value="photos-liked">Photos liked by</option>
                    <option value="photos-commented">Photos commented by</option>
                    <option value="photos-of">Photos with tag of</option>
                    <option value="stories-commented">Posts commented by</option>
                    <option value="events">Common events among</option>
                    <option value="groups">Common groups among</option>
                    <option value="apps-used">Apps used by</option>
                </select>
                <select onchange="generate_url_common();" id="select-common-who1" class="select-item">
                    <option value="0">-- Choose who</option>
                </select>
                <strong>and</strong>
                <select onchange="generate_url_common();" id="select-common-who2" class="select-item">
                    <option value="0">-- Choose who</option>
                </select>
                <a href="#" target="_blank" id="btn-search-common" class="button">Search</a>
            </p>

            <!-- ALL POSTINGS -->
            <h3>All wall postings</h3>
            <p>
                Search all wall postings for
                " <input onkeyup="generate_url_postings();" type="text" id="input-postings-what" />"
                made by or with
                <select onchange="generate_url_postings();" id="select-postings-who" class="select-item">
                    <option value="0">everyone</option>
                </select>
                <a href="#" target="_blank" id="btn-search-postings" class="button">Search</a>
            </p>

        </section>
    </main>


    <!-- FOOTER -->
    <footer>
        <p>
            Developed by <a href="mailto:tormund.gerhardsen@gmail.com">Tormund Gerhardsen</a>
            after inspiration from <a target="_blank" href="http://graph.tips/">graph.tips</a>.
            <br />
            This project is licenced under
            <a target="_blank" href="https://creativecommons.org/licenses/by-nc-sa/4.0/">CC BY-NC-SA</a>
            and avaliable on
            <a target="_blank" href="https://github.com/tormundgerhardsen/facebook-graph-search-generator">GitHub</a>.
            <br />
            Version 1.1 - May 2016
        </p>
        <p>
            <img src="img/cc_license.png" />
        </p>
    </footer>


    <!-- SCRIPT -->
    <script>
        /**
         * Add item to list
         */
        var $btn_add_item = $('#btn-add-item');
        $btn_add_item.click(function() {

            var $input_friendlyname = $('#input-friendlyname');
            var $input_username_or_uid = $('#input-username-or-uid');
            var $loading_add_item = $('#loading-add-item');
            $loading_add_item.show();
            $btn_add_item.hide();

            // Get UID
            $.get('controller.php', {
                request: 'add-fb-item',
                var1: $input_friendlyname.val(),
                var2: $input_username_or_uid.val()

            }).done(function(data) {
                var result = jQuery.parseJSON(data);

                // Add to list and dropdown
                add_fb_item(result.friendlyname, result.fb_uid);

                // Reset form
                $input_friendlyname.val('');
                $input_username_or_uid.val('');
                $loading_add_item.hide();
                $btn_add_item.show();
            });
        });


        /**
         * Add item to list and dropdown
         */
        function add_fb_item(friendlyname, fb_uid) {

            // Add to list
            $('#fb-items').append(
                '<tr class="uid-'+fb_uid+'">'+
                    '<td>'+friendlyname+'</td>'+
                    '<td>'+fb_uid+'</td>'+
                    '<td><span class="link-remove" onclick="remove_fb_item('+fb_uid+')">remove</span></td>' +
                '</tr>'
            );
            // Add to all dropdowns
            $('.select-item').append(
                '<option class="uid-'+fb_uid+'" value="'+fb_uid+'">'+friendlyname+'</option>'
            );
        }


        /**
         * Remove item from list, dropdowns and cookie
         */
        function remove_fb_item(fb_uid) {

            $.get('controller.php', {
                request: 'remove-fb-item',
                var1: fb_uid

            }).done(function(data) {

                // Remove from list and dropdows
                $('.uid-'+fb_uid).remove();
            });
        }


        /**
         * Search: personal
         */
        function generate_url_personal() {

            var what = $('#select-personal-what').val();
            var who = $('#select-personal-who').val();
            var optional = '';

            // Add optional?
            var with_who = $('#select-personal-with-who').val();
            if(with_who != '0') {
                optional = with_who;
            }

            var go_to_url = 'https://www.facebook.com/search/'+who+'/'+optional+what;
            $('#btn-search-personal').attr('href', go_to_url);
        }


        /**
         * Search: common
         */
        function generate_url_common() {

            var what = $('#select-common-what').val();
            var who1 = $('#select-common-who1').val();
            var who2 = $('#select-common-who2').val();

            var go_to_url = 'https://www.facebook.com/search/'+who1+'/'+what+'/'+who2+'/'+what+'/intersect';
            $('#btn-search-common').attr('href', go_to_url);
        }


        /**
         * Search: postings
         */
        function generate_url_postings() {

            var what = $('#input-postings-what').val();
            var go_to_url = 'https://www.facebook.com/search/str/'+what+'/stories-keyword/';

            // Everyone or spesific person?
            var who = $('#select-postings-who').val();
            if(who != '0') {
                go_to_url += who+'/stories/intersect';
            }

            $('#btn-search-postings').attr('href', go_to_url);
        }


        /**
         * Generated by cookie data, and update search parameters to first avaliable
         */
        <?php
        if ($cookie_data) {
            foreach ($cookie_data as $fb_uid => $friendlyname) {
            ?>
                add_fb_item('<?php echo $friendlyname; ?>', <?php echo $fb_uid; ?>);
            <?php
            }
        }
        ?>
        generate_url_personal();
        generate_url_common();
    </script>

</body>
</html>