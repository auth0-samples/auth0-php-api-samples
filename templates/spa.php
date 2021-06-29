<!DOCTYPE html>
<html lang="eng">

    <head>
        <meta charset="UTF-8" />
        <meta name="viewport" content="width=device-width, initial-scale=1.0" />
        <link href="//unpkg.com/tailwindcss@^2/dist/tailwind.min.css" rel="stylesheet">
        <script src="https://cdn.auth0.com/js/auth0-spa-js/1.13/auth0-spa-js.production.js"></script>

        <title>Auth0 PHP Quickstart for Backend APIs</title>

        <style>
            .text-auth0 {
                color: #635dff;
            }

            .bg-auth0 {
                background-color: #635dff;
            }

            textarea {
                min-height: 15em;
            }
        </style>
    </head>

    <body class="home">
        <div class="relative bg-gray-900 overflow-hidden">
            <div class="hidden sm:block sm:absolute sm:inset-0" aria-hidden="true">
                <svg class="absolute bottom-0 right-0 transform translate-x-1/2 mb-48 text-gray-700 lg:top-0 lg:mt-28 lg:mb-0 xl:transform-none xl:translate-x-0" width="364" height="384" viewBox="0 0 364 384" fill="none">
                    <defs>
                        <pattern id="eab71dd9-9d7a-47bd-8044-256344ee00d0" x="0" y="0" width="20" height="20" patternUnits="userSpaceOnUse">
                            <rect x="0" y="0" width="4" height="4" fill="currentColor" />
                        </pattern>
                    </defs>
                    <rect width="364" height="384" fill="url(#eab71dd9-9d7a-47bd-8044-256344ee00d0)" />
                </svg>
            </div>
            <div class="relative pt-6 pb-16 sm:pb-24">
                <div>
                    <nav class="relative max-w-7xl mx-auto flex items-center justify-between px-4 sm:px-6" aria-label="Global">
                        <div class="flex items-center flex-1">
                            <div class="flex items-center justify-between w-full md:w-auto">
                                <a href="#">
                                    <span class="sr-only">Auth0</span>
                                    <svg width="81" height="24" viewBox="0 0 81 24" fill="none" xmlns="http://www.w3.org/2000/svg">
                                        <path d="M18.5334 0H10.7619L13.1617 7.51092H20.9333L14.6477 11.984L17.0475 19.5348C21.0922 16.5767 22.4192 12.0956 20.9333 7.50295L18.5334 0ZM0.590492 7.51092H8.36206L10.7619 0H2.9903L0.590492 7.51092C-0.895483 12.1036 0.431564 16.5846 4.47628 19.5428L6.87609 11.992L0.590492 7.51092ZM4.47628 19.5348L10.7619 23.9999L17.0475 19.5348L10.7619 14.99L4.47628 19.5348ZM63.1286 7.31956C61.293 7.31956 60.1169 8.15677 59.5686 9.52022H59.4256V3.34085H56.6682V19.5029H59.4891V12.4863C59.4891 10.7481 60.5619 9.75145 62.0797 9.75145C63.5577 9.75145 64.4318 10.7003 64.4318 12.3189V19.5029H67.2527V11.8484C67.2448 8.93816 65.6078 7.31956 63.1286 7.31956ZM74.8733 3.30895C71.0591 3.30895 68.7546 6.30694 68.7467 11.5136C68.7467 16.7361 71.0352 19.774 74.8733 19.774C78.7035 19.782 81 16.7441 81 11.5136C80.9921 6.31492 78.6876 3.30895 74.8733 3.30895ZM71.663 11.5136C71.6709 7.71026 72.9026 5.70894 74.8733 5.70894C76.2242 5.70894 77.2255 6.63385 77.7261 8.42786L71.7107 12.8132C71.6789 12.4146 71.663 11.976 71.663 11.5136ZM74.8733 17.3421C73.4827 17.3421 72.4576 16.3534 71.9729 14.4398L78.0201 10.0305C78.0678 10.493 78.0916 10.9873 78.0916 11.5215C78.0916 15.3488 76.8599 17.3421 74.8733 17.3421ZM43.9698 14.4557C43.9698 16.2816 42.6666 17.1906 41.419 17.1906C40.0602 17.1906 39.1623 16.2258 39.1623 14.7029V7.49498H36.3413V15.1415C36.3413 18.0278 37.9783 19.6624 40.3304 19.6624C42.1263 19.6624 43.3818 18.7135 43.9301 17.374H44.0572V19.5109H46.7908V7.49498H43.9698V14.4557ZM29.9603 7.31159C27.4413 7.31159 25.5104 8.43584 24.8906 10.6285L27.5208 11.0033C27.7989 10.182 28.5936 9.48035 29.9762 9.48035C31.2874 9.48035 32.0026 10.1501 32.0026 11.3302V11.378C32.0026 12.1913 31.1523 12.2312 29.0386 12.4544C26.7182 12.7016 24.4932 13.4033 24.4932 16.1062C24.4932 18.4664 26.2176 19.7182 28.4982 19.7182C30.3736 19.7182 31.494 18.8331 32.0105 17.8285H32.1059V19.479H34.8156V11.4418C34.8235 8.2684 32.2489 7.31159 29.9603 7.31159ZM32.0105 15.1654C32.0105 16.5049 30.9378 17.6371 29.2372 17.6371C28.0612 17.6371 27.2188 17.0949 27.2188 16.0584C27.2188 14.974 28.1645 14.5195 29.42 14.3361C30.159 14.2325 31.637 14.0491 32.0105 13.7461V15.1654ZM52.8142 4.48901H49.9932V7.49498H48.2847V9.75145H49.9932V16.4012C49.9773 18.6577 51.6143 19.774 53.7359 19.7102C54.3717 19.6943 54.8484 19.5906 55.1742 19.4949V17.2544C54.9597 17.2863 54.4591 17.3501 54.1015 17.3581C53.3942 17.374 52.8221 17.1109 52.8221 15.9627V9.75145H55.1742V7.50295H52.8142V4.48901Z" fill="#fff"></path>
                                    </svg>
                                </a>
                            </div>
                            <div class="hidden space-x-10 md:flex md:ml-10">
                                <a href="#" class="font-medium text-white hover:text-gray-300">Documentation</a>
                                <a href="#" class="font-medium text-white hover:text-gray-300">GitHub</a>
                            </div>
                        </div>
                        <div class="hidden md:flex">
                            <button id="login" class="hidden inline-flex items-center px-4 py-2 border border-transparent text-sm font-medium rounded-md text-white bg-gray-600 hover:bg-gray-700">Log in</button>
                            <button id="logout" class="hidden inline-flex items-center px-4 py-2 border border-transparent text-sm font-medium rounded-md text-white bg-gray-600 hover:bg-gray-700">Log out</button>
                        </div>
                    </nav>
                </div>

                <main class="mt-16 sm:mt-24">
                    <div class="px-4 sm:px-6 sm:text-center md:max-w-7xl md:mx-auto lg:col-span-6 lg:text-left lg:flex lg:items-center">
                        <div>
                            <a href="#" class="inline-flex items-center text-white bg-gray-800 rounded-full p-1 pr-2 sm:text-base lg:text-sm xl:text-base hover:text-gray-200">
                                <span id="state" class="px-3 py-0.5 text-white text-xs font-semibold leading-5 uppercase tracking-wide bg-gray-500 rounded-full">Loading...</span>
                                <span class="ml-4 text-sm">PHP Quickstart</span>
                                <svg class="ml-2 w-5 h-5 text-gray-500" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" fill="currentColor" aria-hidden="true">
                                    <path fill-rule="evenodd" d="M7.293 14.707a1 1 0 010-1.414L10.586 10 7.293 6.707a1 1 0 011.414-1.414l4 4a1 1 0 010 1.414l-4 4a1 1 0 01-1.414 0z" clip-rule="evenodd" />
                                </svg>
                            </a>

                            <h1 class="mt-4 text-4xl tracking-tight font-extrabold text-white sm:mt-5 sm:leading-none lg:mt-6 lg:text-5xl xl:text-6xl">
                                <span class="md:block">Welcome</span>
                                <span id="name" class="text-gray-400 md:block">Guest</span>
                            </h1>

                            <p id="note" class="mt-3 text-base text-gray-300 sm:mt-5 sm:text-xl lg:text-lg xl:text-xl"></p>
                        </div>
                    </div>
                </main>
            </div>
        </div>

        <main class="mt-4 md:mt-16">
            <div class="px-4 sm:px-6 sm:text-center md:max-w-7xl md:mx-auto lg:col-span-6 lg:text-left lg:flex lg:items-center">
                <div class="mb-16 w-full lg:grid lg:grid-cols-12 lg:gap-x-5">
                    <aside class="py-6 px-2 sm:px-6 lg:py-0 lg:px-0 lg:col-span-3">
                        <nav class="space-y-1">
                            <a href="#user" class="bg-gray-50 text-indigo-700 hover:text-indigo-700 hover:bg-white group rounded-md px-3 py-2 flex items-center text-sm font-medium" aria-current="page">
                                <svg class="text-indigo-500 group-hover:text-indigo-500 flex-shrink-0 -ml-1 mr-3 h-6 w-6" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor" aria-hidden="true">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5.121 17.804A13.937 13.937 0 0112 16c2.5 0 4.847.655 6.879 1.804M15 10a3 3 0 11-6 0 3 3 0 016 0zm6 2a9 9 0 11-18 0 9 9 0 0118 0z" />
                                </svg>
                                <span class="truncate">
                                    Example API Response
                                </span>
                            </a>
                        </nav>
                    </aside>

                    <div class="space-y-6 sm:px-6 lg:px-0 lg:col-span-9">
                        <div id="cookies">
                            <div class="shadow sm:rounded-md sm:overflow-hidden">
                                <div class="bg-white">
                                    <div class="px-4 py-5 sm:px-6 bg-gray-100">
                                        <h3 class="text-lg leading-6 font-medium text-gray-900">Example API Response</h3>
                                    </div>

                                    <div class="px-4 py-5 sm:px-6 grid grid-cols-6 gap-6">
                                        <div class="col-span-6">
                                            <textarea id="backendApiResponse" readonly="readonly" class="mt-1 block w-full bg-white border border-gray-300 rounded-md shadow-sm py-2 px-3 focus:outline-none focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm"></textarea>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </main>

        <script>
            createAuth0Client({
                domain: '<?php echo $config->getDomain(); ?>',
                client_id: '<?php echo $config->getClientId(); ?>',
                audience: '<?php echo $config->buildDefaultAudience(); ?>',
                redirect_uri: '<?php echo $router->getUri('/', ''); ?>',
                scope: '<?php echo $config->buildScopeString() ?>'
            }).then(auth0 => {
                document.getElementById('login').addEventListener('click', () => {
                    auth0.loginWithRedirect().catch(() => {});
                });

                document.getElementById('logout').addEventListener('click', () => {
                    auth0.logout();
                });

                auth0.getUser().then(user => {
                    if (user) {
                        auth0.getUser().then(user => {
                            setAppState(true, user);

                            // Issue a request to our quickstart backend api at /api
                            callBackendApi(auth0);
                        });

                        return;
                    }

                    auth0.handleRedirectCallback().then(redirectResult => {
                        console.log(redirectResult);

                        auth0.getUser().then(user => {
                            setAppState(true, user);

                            // Issue a request to our quickstart backend api at /api
                            callBackendApi(auth0);
                        });
                    }).catch(() => {
                        setAppState(false);
                        callBackendApi();
                    });
                });
            });

            function setAppState(loggedIn, user) {
                var name = document.getElementById('name');
                var note = document.getElementById('note');
                var state = document.getElementById('state');
                var login = document.getElementById('login');
                var logout = document.getElementById('logout');

                if (loggedIn === true) {
                    state.classList.remove('bg-gray-500');
                    state.classList.add('bg-green-500');
                    state.innerText = 'Logged in';

                    login.classList.add('hidden');
                    logout.classList.remove('hidden');

                    name.classList.remove('text-gray-400');
                    name.classList.add('text-auth0');

                    note.innerText = 'You are authenticated.';

                    if (user) {
                        name.innerHTML = '<span class="inline-block relative"><img class="h-12 w-12 rounded-full" src="' + user.picture + '"></span> ' + user.name;
                        note.innerHTML = 'You successfully authenticated as <span class="font-mono bg-gray-800 p-1 rounded text-sm">' + user.sub + '</span>.';
                    }

                    return;
                }

                state.classList.remove('bg-green-500');
                state.classList.add('bg-gray-500');
                state.innerText = 'Logged Out';

                note.innerText = 'You are not authenticated.';

                logout.classList.add('hidden');
                login.classList.remove('hidden');
            }

            function callBackendApi(auth0) {
                if (auth0) {
                    auth0
                    .getTokenSilently()
                    .then(accessToken =>
                        fetch('/api', {
                            method: 'GET',
                            headers: {
                                Authorization: `Bearer ${accessToken}`
                            }
                        })
                    )
                    .then(response => response.json())
                    .then(response => {
                        document.getElementById('backendApiResponse').value = JSON.stringify(response, undefined, 4);
                    });

                    return;
                }

                fetch('/api', {
                    method: 'GET'
                }).then(response => response.json())
                .then(response => {
                    document.getElementById('backendApiResponse').value = JSON.stringify(response, undefined, 4);
                });
            }
        </script>
    </body>

</html>
