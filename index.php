<?php
require_once('model.php');
$FbGSG = new FbGSG();
$cookie_data = $FbGSG->get_cookie_data();
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Facebook Graph Search Generator</title>
    <link rel="stylesheet" type="text/css" href="style.css" />
    <script type="text/javascript" src="js/jquery-2.2.3.min.js"></script>
</head>
<body>

    <div id="wrap">

        <h1>Facebook Graph Search Generator</h1>

        <!-- GETTING STARTED -->
        <section>
            <h2>1. Getting started</h2>
            <p><strong>Set the lanuage of your Facebook Profile to "English (US)"</strong></p>
            <p>
                <strong>Why not just type names directly?</strong><br />
                Well, a lot of stuff can have the same name. Thats why we stick
                to unique usernames and/or UIDs. Everything on Facebook (users, places, events, etc.) have a
                Unique ID, hereby just called UIDs. This is simply just a number, but very important to get the
                search accurate.
            </p>
        </section>

        <!-- ADD & LIST ITEMS -->
        <section>
            <h2>2. Add people by unique username or UID</h2>
            <!-- TODO: Add examples -->

            <p>
                <input type="text" id="input-friendlyname" name="friendlyname" placeholder="Friendly name">
                <input type="text" id="input-username-or-uid" name="username-or-uid" placeholder="Username/UID">
                <span id="btn-add-item" class="button">Add</span>
                <img id="loading-add-item" class="loading" src="img/loading.gif" width="24" />
            </p>

            <table id="fb-items" cellpadding="0" cellspacing="2">
                <tr>
                    <th>Friendly name</th>
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
            <p class="search-p">
                <select onchange="generate_url_personal();" id="select-personal-what">
                    <option value="stories-commented">Posts commented by</option>
                    <option value="photos-liked">Photos liked by</option>
                    <option value="photos-of">Photos made of</option>
                    <option value="photos-tagged">Photos with tag of</option>
                    <option value="photos-commented">Photos commented by</option>
                    <option value="photos-commented">Photos uploaded by friends of</option>
                </select>
                <select onchange="generate_url_personal();" id="select-personal-who" class="select-item">
                    <option value="0">-- Choose who</option>
                </select>
                <a href="#" target="_blank" id="btn-search-personal" class="button">Search</a>
            </p>

            <!-- COMMON -->
            <h3>What do they have in common?</h3>
            <p class="search-p">
                <select onchange="generate_url_common();" id="select-common-what">
                    <option value="places-visited">Places visited by</option>
                    <option value="pages-liked">Pages liked by</option>
                    <option value="photos-liked">Photos liked by</option>
                    <option value="photos-commented">Photos commented by</option>
                    <option value="photos-of">Photos with tag of</option>
                    <option value="stories-commented">Posts commented by</option>
                    <option value="events">Common events among</option>
                    <option value="groups">Common groups among</option>
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
        </section>
    </div>


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

            var go_to_url = 'https://www.facebook.com/search/'+who+'/'+what;
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