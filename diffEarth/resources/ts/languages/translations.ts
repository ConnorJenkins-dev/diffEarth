// translations.ts
type Translations = {
    [key: string]: {
        map: string; //Header
        aboutUs: string;
        resources: string;
        contactUs: string;
        login: string;
        logout: string;
        controlpnl: string;
        //login

        YouAreloggedInAs: string;

        dontHaveAccount: string;

        createAccount: string;
        fullName: string;
        email: string;
        password: string;
        confirmPassword: string;
        signUp: string;
        close: string;
        invalidEmail: string;
        emailInUse: string;
        passwordShort: string;
        passwordMismatch: string;
        registrationFailed: string;

        // File Upload
        uploadCsv: string;
        uploadFile: string;
        chooseFile: string;
        selectFileError: string;
        uploading: string;
        upload: string;
        fileSizeError: string;
        cancel: string;
        // 404 Page
        pageNotFound: string;
        pageNotFoundMessage: string;
        returnHome: string;
        // Email Form
        title: string;
        locationLabel: string;
        columnLabel: string;
        thresholdLabel: string;
        emailsLabel: string;
        submitButton: string;
        empty: string;
        commaSeparated: string;
        invalid: string;
        pleaseEnter: string;
        sendFailure: string;
        // Datasets
        loading: string;
        selectDataset: string;
        showAllDatasets: string;
        name: string;
        description: string;
        lastUpdated: string;
        createdAt: string;
        previous: string;
        next: string;
        page: string;
        of: string;
        //graph
        graphTitle: string;
        xTitle: string;
        yTitle: string;
        graphTypeScatter: string;
        graphTypeBar: string;
        importData: string;
        resetChart: string;
        startDate: string;
        endDate: string;
        search: string;
        filterByDate: string;
        selDataset: string;
        selectCategory: string;
        newTraceName: string;
        plotSize: string;
        biography: string;

        // Deployment.vue
        searchPlaceholder: string;
        goToLocation: string;
        noDeploymentsFound: string;
        //MapPage.vue
        polyGlobe: string;
        mapGlobe: string;
        //contact us
        contactUsMessage: string;
        contactUsTitle: string;
        githubTitle: string;
        githubDescription: string;
        githubLinkText: string;
        linkedinTitle: string;
        linkedinDescription: string;
        linkedinLinkText: string;
        mastodonTitle: string;
        mastodonDescription: string;
        mastodonLinkText: string;
        blueskyTitle: string;
        blueskyDescription: string;
        blueskyLinkText: string;
        cardiffUniversityTitle: string;
        cardiffUniversityDescription: string;
        cardiffUniversityLinkText: string;
        // Resources Page
        resourcesTitle: string;
        resourcesIntro: string;
        cryoeggTitle: string;
        cryowurstTitle: string;
        cryoeggMadeTitle: string;
        cryowurstMadeTitle: string;
        cryoeggDeployment: string;
        cryowurstDeployment: string;
        surfaceReceiver: string;
        //weather
        temperature: string;

        condition: string;
        deployment: string;
        descriptioncol: string;
        editDashboard: string;
        loadingWeather: string;
        namecol: string;
        publishShareDashboard: string;
        setupEmailAlerts: string;
        save: string;
    };
};

// The translations object
export const translations: Translations = {
    en: {
        map: "Map", //Header
        aboutUs: "About Us",
        resources: "Resources",
        contactUs: "Contact Us",
        login: "Log In",
        logout: "Log out",
        controlpnl: "Control Panel",

        //login
        close: "Close",
        YouAreloggedInAs: "You are logged in as ",
        email: "Email",
        password: "Password",
        dontHaveAccount: "Don't have an account?",
        signUp: "Sign Up",
        createAccount: "Create an Account",
        fullName: "Full Name",
        confirmPassword: "Confirm Password",
        invalidEmail: "Invalid email format. Please enter a valid email.",
        emailInUse: "Email already in use.",
        passwordShort:
            "Password and Confirm Password must be at least 6 characters long.",
        passwordMismatch: "Password and Confirm Password do not match.",
        registrationFailed: "Registration failed. Please try again.",
        // File Upload
        uploadFile: "Upload File",
        uploadCsv: "Upload CSV",
        chooseFile: "Choose a file to upload",
        selectFileError: "Please select a file to upload",
        uploading: "Uploading...",
        upload: "Upload",
        fileSizeError: "File is too large. Maximum allowed size is 20MB.",
        cancel: "Cancel",
        // 404 Page
        pageNotFound: "404",
        pageNotFoundMessage: "The page you attempted to go to does not exist.",
        returnHome: "Return to home",
        // Email Form
        title: "Send Email Notification",
        locationLabel: "Location Name",
        columnLabel: "Column",
        thresholdLabel: "Threshold",
        emailsLabel: "Emails (comma-separated)",
        submitButton: "Send Email",
        empty: "Please enter at least one email.",
        commaSeparated:
            "Emails must be comma-separated. Please separate multiple emails with commas.",
        invalid: "Invalid email: ",
        pleaseEnter: " Please enter a valid email.",
        sendFailure: "Failed to send email",
        // Datasets
        loading: "Loading datasets...",
        selectDataset: "Select Dataset:",
        showAllDatasets: "Show All Datasets",
        name: "Name",
        description: "Description",
        lastUpdated: "Last Updated",
        createdAt: "Created At",
        previous: "Previous",
        next: "Next",
        page: "Page",
        of: "of",
        //graph
        graphTitle: "Graph Title",
        xTitle: "X-Axis Title",
        yTitle: "Y-Axis Title",
        graphTypeScatter: "Scatter",
        graphTypeBar: "Bar",
        importData: "Import data from database",
        resetChart: "Reset Chart",
        startDate: "Start date: ",
        endDate: "End date: ",
        search: "Search",
        filterByDate: "Filter by date",
        selDataset: "Select Dataset",
        selectCategory: "Select Category",
        newTraceName: "New trace name...",
        plotSize: "Plot size: ",
        biography: "Biography",

        // Deployment.vue
        searchPlaceholder: "Search by name...",
        goToLocation: "Go to Location",
        noDeploymentsFound: "No deployments found.",
        //mapPage.vue
        polyGlobe: "Poly Globe", // Add translation for Poly Globe
        mapGlobe: "Map Globe",
        //contact us
        contactUsMessage:
            "To reach out to us, please contact us via the following channels:",
        contactUsTitle: "Contact Us",
        githubTitle: "GitHub",
        githubDescription:
            "Use this link to view how to decode raw CHIL instrument data packets, create and maintain MariaDB databases for output from cryo* instruments, view and download CHIL instrument data, configure files for setting up Docker containers for testing MariaDB, regulating voltage, and more.",
        githubLinkText: "Visit our GitHub Repository",
        linkedinTitle: "LinkedIn",
        linkedinDescription:
            "We use LinkedIn to connect professionally, share updates, and engage with our community.",
        linkedinLinkText: "Connect with us on LinkedIn",
        mastodonTitle: "Mastodon",
        mastodonDescription:
            "Follow us on Mastodon for updates and discussions.",
        mastodonLinkText: "Follow us on Mastodon",
        blueskyTitle: "Bluesky",
        blueskyDescription: "Join our conversations on Bluesky.",
        blueskyLinkText: "Join us on Bluesky",
        cardiffUniversityTitle: "Cardiff University",
        cardiffUniversityDescription:
            "Learn more about Dr. Michael Prior-Jones and his work at Cardiff University.",
        cardiffUniversityLinkText: "Visit Cardiff University Profile",
        // Resources Page
        resourcesTitle: "Resources",
        resourcesIntro:
            "We design and test wireless instruments for observing glaciers and ice sheets.",
        cryoeggTitle: "Cryoeggs to observe subglacial hydrology",
        cryowurstTitle: "Cryowurst to observe the interior of a glacier",
        cryoeggMadeTitle: "How is the Cryoegg made?",
        cryowurstMadeTitle: "How is the Cryowurst made?",
        cryoeggDeployment:
            "We use Cryoeggs to observe subglacial hydrology. Using moulins, we deploy cryoeggs from the surface into the internal hydrological system.",
        cryowurstDeployment:
            "We drill boreholes, and deploy Cryowurst to measure the properties of ice within the column.",
        surfaceReceiver:
            "The instruments transmit data via a radio link to a receiver on the surface, which then sends the data back to us via satellite.",
        temperature: "temperature",
        condition: "Condition: ",
        deployment: "Deployment: ",
        descriptioncol: "Description: ",
        editDashboard: "Edit Dashboard: ",
        loadingWeather: "Loading weather...",
        namecol: "Name: ",
        publishShareDashboard: "Publish and Share Dashboard",
        setupEmailAlerts: "Setup Email Alerts",
        save: "Save",
    },
    cy: {
        map: "Gweiadur", //Header
        aboutUs: "Amdanom Ni",
        resources: "Adnoddau",
        contactUs: "Cysylltwch â Ni",
        login: "Mewngofnodi",
        logout: "Allgofnodi",
        controlpnl: "Panel Rheoli",
        //login
        close: "Cau",
        YouAreloggedInAs: "Rydych chi wedi mewngofnodi fel ",
        email: "Ebost",
        password: "Cyfrinair",
        dontHaveAccount: "Heb gofnod?",
        signUp: "Cofrestru",
        createAccount: "Creu Cyfrif",
        fullName: "Enw Llawn",
        confirmPassword: "Cadarnhau Cyfrinair",
        invalidEmail: "Fformat ebost annilys. Rhowch ebost dilys.",
        emailInUse: "Mae'r ebost yn cael ei ddefnyddio eisoes.",
        passwordShort:
            "Rhaid i'r cyfrinair a'r cadarnhad cyfrinair fod o leiaf 6 nod.",
        passwordMismatch:
            "Nid yw'r cyfrinair a'r cadarnhad cyfrinair yn cyfateb.",
        registrationFailed: "Methu â chofrestru. Rhowch gynnig arall.",
        // File Upload
        uploadFile: "Llwytho Ffeil",
        uploadCsv: "Llwytho CSV",
        chooseFile: "Dewiswch ffeil i'w lwytho",
        selectFileError: "Dewiswch ffeil i'w lwytho",
        uploading: "Llwytho...",
        upload: "Llwytho",
        fileSizeError:
            "Mae'r ffeil yn rhy fawr. Mae'r maint mwyaf caniatiedig yn 20MB.",
        cancel: "Canslo",
        // 404 Page
        pageNotFound: "404",
        pageNotFoundMessage: "Nid yw'r dudalen yr ymdriniwch â hi yn bodoli.",
        returnHome: "Dychwelyd i'r cartref",
        // Email Form
        title: "Anfon Hysbysiad Ebost",
        locationLabel: "Enw Lleoliad",
        columnLabel: "Colofn",
        thresholdLabel: "Trothwy",
        emailsLabel: "E-byst (separu gyda coma)",
        submitButton: "Anfon Ebost",
        empty: "Rhowch e-bost o leiaf.",
        commaSeparated:
            "Rhaid i'r e-byst fod yn separu gyda coma. Gwnewch yn siŵr bod e-byst lluosog yn cael eu seperu.",
        invalid: "E-bost annilys: ",
        pleaseEnter: " Rhowch e-bost dilys.",
        sendFailure: "Methwyd â chynnwys yr e-bost",
        // Datasets
        loading: "Llwytho setiau data...",
        selectDataset: "Dewiswch Set Ddata:",
        showAllDatasets: "Dangos Pob Set Ddata",
        name: "Enw",
        description: "Disgrifiad",
        lastUpdated: "Diweddarwyd Ddiwethaf",
        createdAt: "Crëwyd Ar",
        previous: "Blaenorol",
        next: "Nesaf",
        page: "Tudalen",
        of: "o",
        //graph
        graphTitle: "Teitl y Graff",
        xTitle: "Teitl Echel-X",
        yTitle: "Teitl Echel-Y",
        graphTypeScatter: "Gwasgariad",
        graphTypeBar: "Bar",
        importData: "Mewnforio data o'r gronfa ddata",
        resetChart: "Ailosod y Siart",
        startDate: "Dyddiad cychwyn: ",
        endDate: "Dyddiad gorffen: ",
        search: "Chwilio",
        filterByDate: "Hidlo yn ôl dyddiad",
        selDataset: "Dewiswch Set Ddata",
        selectCategory: "Dewiswch Gategori",
        newTraceName: "Enw olrhain newydd...",
        plotSize: "Maint y plot: ",
        biography: "Bywgraffiad",

        // Deployment.vue
        searchPlaceholder: "Chwilio gan enw...",
        goToLocation: "Ewch i'r Lleoliad",
        noDeploymentsFound: "Dim deploymentau wedi'u canfod.",
        //MapPage.vue
        polyGlobe: "Poly Glofa",
        mapGlobe: "Glofa Map",
        //contact us
        contactUsMessage:
            "I estyn allan atom, cysylltwch â ni trwy'r sianeli canlynol",
        contactUsTitle: "Cysylltwch â Ni",
        githubTitle: "GitHub",
        githubDescription:
            "Defnyddiwch y ddolen hon i weld sut i ddaddecodeu pecynnau data offer CHIL crai, creu a chynnal cronfeydd data MariaDB ar gyfer allbwn o offer cryo* , edrych a llwytho data offer CHIL, creu ffeiliau i osod cydnawsedd Docker ar gyfer prawf MariaDB, rheoleiddio foltedd, a mwy.",
        githubLinkText: "Ymweld â'n Cronfa GitHub",
        linkedinTitle: "LinkedIn",
        linkedinDescription:
            "Defnyddiwn LinkedIn i gysylltu'n broffesiynol, rhannu diweddariadau, a chymryd rhan yn ein cymuned.",
        linkedinLinkText: "Cysylltwch â ni ar LinkedIn",
        mastodonTitle: "Mastodon",
        mastodonDescription:
            "Dilynwch ni ar Mastodon am ddiweddariadau a thrafodaethau.",
        mastodonLinkText: "Dilynwch ni ar Mastodon",
        blueskyTitle: "Bluesky",
        blueskyDescription: "Ymunwch yn ein sgwrsiau ar Bluesky.",
        blueskyLinkText: "Ymunwch â ni ar Bluesky",
        cardiffUniversityTitle: "Prifysgol Caerdydd",
        cardiffUniversityDescription:
            "Dysgwch fwy am Dr. Michael Prior-Jones a'i waith yn Prifysgol Caerdydd.",
        cardiffUniversityLinkText: "Ymweld â'r Proffil Prifysgol Caerdydd",
        // Resources Page
        resourcesTitle: "Adnoddau",
        resourcesIntro:
            "Rydym yn dylunio a phrofi offerynnau diwifr ar gyfer arsylwi rhewlifoedd a dalennau iâ.",
        cryoeggTitle: "Cryoeggs i arsylwi hydroleg is-rhewlifol",
        cryowurstTitle: "Cryowurst i arsylwi mewnol rhewlif",
        cryoeggMadeTitle: "Sut mae'r Cryoegg yn cael ei wneud?",
        cryowurstMadeTitle: "Sut mae'r Cryowurst yn cael ei wneud?",
        cryoeggDeployment:
            "Rydym yn defnyddio Cryoeggs i arsylwi hydroleg is-rhewlifol. Gan ddefnyddio moulins, rydym yn defnyddio cryoeggs o'r arwyneb i'r system hydrolegol fewnol.",
        cryowurstDeployment:
            "Rydym yn drilio tyllau turio, ac yn defnyddio Cryowurst i fesur priodweddau iâ o fewn y golofn.",
        surfaceReceiver:
            "Mae'r offerynnau'n trosglwyddo data trwy ddolen radio i dderbynnydd ar yr arwyneb, sydd wedyn yn anfon y data yn ôl atom trwy loeren.",

        temperature: " tymheredd: ",
        condition: "Cyflwr: ",
        deployment: "Deployment: ", // You might want to verify if there's a Welsh equivalent
        descriptioncol: "Disgrifiad: ",
        editDashboard: "Golygu Dangosfwrdd",
        loadingWeather: "Llwytho tywydd...",
        namecol: "Enw",
        publishShareDashboard: "Cyhoeddi a Rhannu Dangosfwrdd",
        setupEmailAlerts: "Gosod Rhybuddion Ebost",
        save: "Cadw",
    },
};
