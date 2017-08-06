cd "$( dirname "$0" )"
set -e

wp db export /tmp/$(date "+%Y%m%dT%H%M%S").sql
wp post delete --force $( wp post list --post_type=attachment --format=ids )
wp db reset --yes
wp core install --url="http://src.wordpress-develop.dev/" --title="WordPress Develop" --admin_user=admin --admin_password=admin --admin_email=admin@example.com --skip-email
wp user create otheradmin otheradmin@example.com --role=administrator --user_pass=otheradmin
wp cache flush
wp option set fresh_site 0
wp post delete $( wp post list --post_type=post --format=ids )
wp theme activate twentyseventeen-westonson
wp plugin activate customize-snapshots customize-posts customizer-browser-history user-switching
wp --user=1 eval-file load-starter-content.php

post1=$( wp post create --porcelain --post_title="Gap Bluff" --post_date="2017-06-01" --post_status=publish --post_content="Lorem ipsum dolor sit amet, consectetur adipiscing elit. Nunc commodo condimentum odio. Suspendisse ultrices massa arcu, sed dapibus est pretium eget. Lorem ipsum dolor sit amet, consectetur adipiscing elit. Lorem ipsum dolor sit amet, consectetur adipiscing elit. Praesent sit amet tortor in est pharetra venenatis at a sapien. Nunc non enim condimentum, eleifend urna nec, mollis nisi. Vivamus at rutrum velit." )
post2=$( wp post create --porcelain --post_title="Hoodoo" --post_date="2017-05-01" --post_status=publish --post_content="Lorem ipsum dolor sit amet, consectetur adipiscing elit. Praesent sit amet tortor in est pharetra venenatis at a sapien. Nunc non enim condimentum, eleifend urna nec, mollis nisi. Vivamus at rutrum velit. Lorem ipsum dolor sit amet, consectetur adipiscing elit. Nunc commodo condimentum odio. Suspendisse ultrices massa arcu, sed dapibus est pretium eget. Lorem ipsum dolor sit amet, consectetur adipiscing elit." )
post3=$( wp post create --porcelain --post_title="Columbia River Gorge" --post_date="2017-04-01" --post_status=publish --post_content="Lorem ipsum dolor sit amet, consectetur adipiscing elit. In sodales mollis orci vitae pretium. Curabitur et accumsan ligula. Nulla laoreet eget purus nec volutpat. Praesent id ullamcorper lorem. Nulla fringilla leo dolor, ut sagittis ligula rhoncus eu. Lorem ipsum dolor sit amet, consectetur adipiscing elit. Nunc commodo condimentum odio. Suspendisse ultrices massa arcu, sed dapibus est pretium eget. Lorem ipsum dolor sit amet, consectetur adipiscing elit. Lorem ipsum dolor sit amet, consectetur adipiscing elit." )

wp media import --post_id=$post1 --featured_image ../images/gap-bluff-sydney-australia.jpg
wp media import --post_id=$post2 --featured_image ../images/hoodoo-butte-oregon.jpg
wp media import --post_id=$post3 --featured_image ../images/rowena-crest-oregon.jpg
wp media import ../images/mt-hood-from-trillium-lake.jpg ../images/toronto-skyline.jpg
