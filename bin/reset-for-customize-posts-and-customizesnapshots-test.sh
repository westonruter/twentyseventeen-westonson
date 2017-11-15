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
wp plugin activate customize-snapshots customize-posts customizer-browser-history user-switching customize-object-selector
wp --user=1 eval-file load-starter-content.php

post1=$( wp post create --porcelain --post_title="Gap Bluff" --post_date="2017-05-01" --post_status=publish --post_content="Port Jackson, consisting of the waters of Sydney Harbour, Middle Harbour, North Harbour and the Lane Cove and Parramatta Rivers, is the ria or natural harbour of Sydney, New South Wales, Australia. The harbour is an inlet of the Tasman Sea (part of the South Pacific Ocean). It is the location of the Sydney Opera House and Sydney Harbour Bridge. The location of the first European settlement in Australia, Port Jackson has continued to play a key role in the history and development of Sydney. Source: <a href='https://en.wikipedia.org/wiki/Port_Jackson'>Wikipedia</a>." )
post2=$( wp post create --porcelain --post_title="Three Sisters" --post_date="2017-06-01" --post_status=publish --post_content="The Three Sisters are a complex volcano of three volcanic peaks of the Cascade Volcanic Arc and the Cascade Range in the U.S. state of Oregon. Each exceeding 10,000 feet (3,000 m) in elevation, they are the third-, fourth-, and fifth-highest peaks in the state of Oregon, and are located in the Three Sisters Wilderness, about 10 miles (16 km) south of the nearest town of Sisters. Diverse species of flora and fauna inhabit the area on and around the mountains, which is subject to frequent snowfall, occasional rain, and extreme temperature differences between seasons. The mountains, particularly South Sister, are popular for climbing and scrambling. Source: <a href='https://en.wikipedia.org/wiki/Three_Sisters_(Oregon)'>Wikipedia</a>." )
post3=$( wp post create --porcelain --post_title="Columbia River Gorge" --post_date="2017-04-01" --post_status=publish --post_content="The Columbia River Gorge is a canyon of the Columbia River in the Pacific Northwest of the United States. Up to 4,000 feet (1,200 m) deep, the canyon stretches for over 80 miles (130 km) as the river winds westward through the Cascade Range forming the boundary between the State of Washington to the north and Oregon to the south. Extending roughly from the confluence of the Columbia with the Deschutes River (and the towns of Roosevelt, Washington, and Arlington, Oregon) in the east down to the eastern reaches of the Portland metropolitan area, the water gap furnishes the only navigable route through the Cascades and the only water connection between the Columbia River Plateau and the Pacific Ocean.  Source: <a href='https://en.wikipedia.org/wiki/Columbia_River_Gorge'>Wikipedia</a>." )

wp media import --post_id=$post1 --featured_image ../images/gap-bluff-sydney-australia.jpg
wp media import --post_id=$post2 --featured_image ../images/hoodoo-butte-oregon.jpg
wp media import --post_id=$post3 --featured_image ../images/rowena-crest-oregon.jpg
wp media import ../images/mt-hood-from-trillium-lake.jpg ../images/toronto-skyline.jpg ../images/three-sisters.jpg

say "All done"
