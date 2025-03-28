// translations.ts
type Translations = {
    [key: string]: {
        map: string; //Header
        aboutUs: string;
        community: string;
        resources: string;
        contactUs: string;
        pricing: string;
        login: string;
        logout: string;
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

        //UserDashboard
        button1: string;
        button2: string;
        button3: string;
        button4: string;
        button5: string;
        button6: string;
        button7: string;
        button8: string;
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
    };
};

// The translations object
export const translations: Translations = {
    en: {
        map: "Map", //Header
        aboutUs: "About Us",
        community: "Community",
        resources: "Resources",
        contactUs: "Contact Us",
        pricing: "Pricing",
        login: "Log in",
        logout: "Log out",
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
        //UserDashboard
        button1: "Button 1",
        button2: "Button 2",
        button3: "Button 3",
        button4: "Button 4",
        button5: "Button 5",
        button6: "Button 6",
        button7: "Button 7",
        button8: "Button 8",
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
    },
    cy: {
        map: "Gweiadur", //Header
        aboutUs: "Amdanom Ni",
        community: "Cymuned",
        resources: "Adnoddau",
        contactUs: "Cysylltwch â Ni",
        pricing: "Prisiau",
        login: "Mewngofnodi",
        logout: "Allgofnodi",
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
        //UserDashboard
        button1: "Botwm 1",
        button2: "Botwm 2",
        button3: "Botwm 3",
        button4: "Botwm 4",
        button5: "Botwm 5",
        button6: "Botwm 6",
        button7: "Botwm 7",
        button8: "Botwm 8",
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
    },
};
