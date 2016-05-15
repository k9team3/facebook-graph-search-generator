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
                    <th>Frienly name</th>
                    <th>Facebook UID</th>
                </tr>
            </table>
        </section>

        <!-- SEARCH GENERATED -->
        <section>
            <h2>3. Search</h2>

            <h3>Personal</h3>
            <p>
                <select onchange="generate_url_personal();" id="select-personal-what">
                    <option value="stories-commented">Comments on posts</option>
                    <option value="photos-liked">Photos liked</option>
                </select>
                by
                <select onchange="generate_url_personal();" id="select-personal-who" class="select-item">
                    <option value="0">-- Choose who</option>
                </select>
                <a href="#" target="_blank" id="btn-search-personal" class="button">Search</a>
            </p>
        </section>
    </div>


    <!-- SCRIPT -->
    <script>

        // ADD ITEM
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
                // Add to list
                $('#fb-items').append('<tr><td>'+result.friendlyname+'</td><td>'+result.fb_uid+'</td></tr>');
                // Add to all dropdowns
                $('.select-item').append('<option value="'+result.fb_uid+'">'+result.friendlyname+'</option>');

                // Reset form
                $input_friendlyname.val('');
                $input_username_or_uid.val('');
                $loading_add_item.hide();
                $btn_add_item.show();
            });
        });


        // SEARCH: PERSONAL
        function generate_url_personal() {

            var select_personal_what = $('#select-personal-what').val();
            var select_personal_who = $('#select-personal-who').val();

            var go_to_url = 'https://www.facebook.com/search/'+select_personal_who+'/'+select_personal_what;
            $('#btn-search-personal').attr('href', go_to_url);
        }
        generate_url_personal();
    </script>

</body>
</html>