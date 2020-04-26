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