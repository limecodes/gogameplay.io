Visitor is already created when the page is rendered, so I need to pass the uuid to the React application
On Android, if the user changes their IP, then I need to update the visitor record with the UUID
The uuid is persisted in the local storage.
I can also have a flash for Android if the user is on wifi
        // For iPhone, I don't have access to the connection type so I can only tell by IP
        // I need to make sure that I don't call the IP2Location API more than I need to
        // User then chooses their carrier and proceeds to offer selection

        // TODO: Get the user's geo location by their IP address
        // On Android (network.connection)
        //  -> Check if the user if on wifi
        //      -> If the user is on wifi, ask the user to switch to cellular connection
        //      -> Onchange of network connection, update the IP and lookup the carrier
        // On iPhone
        //  -> Check the user's IP and check if mobile carrier values are there
        //  -> If they are not present, then ask the user to switch to cellular network
        //  -> Check if the IP address changed and do another lookup
        //  -> Have them select their carrier

        // UUID will persist, but IP address may change

        // In order to render the first page, I only need the geolocation to render the list
        // of carriers, I have two options:
        // 1. Trust the user to select their own carrier
        // 2. Do additional checks to ensure that the user is on a cellular connection

        // NEXT TODO:
        // 1. Echo the user IP address
        // 2. Setup React
        // 3. Echo the user's geolocation

        // To make the controller return 422
        // Later, will show a page with an error

        // BUG: Duplicate entry when trying to update if the user has been on the site before
        //      It's not grabbing the uid by previously used ip address
        //      The bug is in firstOrCreate, it's not grabbing a previous user by ip address
        //      This will eat up my API usage, I need to fix this to grab a previous user
        //      Without using firstOrCreate
        //      NO WAIT!!
        //      When a user comes in with wifi, then changes to cellular, it's not grabbing the user
        //      because it's not detecting their cellular IP, since I'm updating it.
        //      A fix would be to add another field or another record, instead of updating it.
        //      OR: I can add an ip_address-to-user_id table

        Next Steps:

        [x] TODO: Cleanup the game controller
        TODO: Fix up the front-end
        TODO: Offers 😈 The fun part begins
        TODO: Don't forget non-mobile

        TODO: Beef up offers tests and do backups offers, focus on the offer part from now on.
        TODO: Back button redirect from offer

        TODO: Gotta change vagrant name and stuff to `gogameplay.local` since I'm combining the front page and not using S3