

<style>
    :root{
        --c1 : #090927;
        --c2: #111030;
        --c2Lighter:#141339;
        --sideBarTitleColor: #636288;
        --sideBarLightColor:#8787ac;
        --hoverColor:#ff516d;
        --authorNameColor:#5e5e88;
        --white:#fff1f3;

        --submitColor: #66BB00;
    }

    *{
        color:var(--sideBarTitleColor);
    }

    body{
        background:var(--c1);
    }

    .contentWrapper{
        position:relative;
        top:0px;
        left:0px;

        width:100vw;
        height:auto;

        background:var(--c1);
    }

    .section{
        position:relative;

        width:auto;

        white-space:break-word;

        border-radius:10px;
        padding:10px;

        margin:0px auto;

        background:var(--c2);
    }

    .section h2{
        color:var(--white);
        text-align:center;
    }

    .section h3{
        color:var(--white);
        text-align:start;
    }

    .list{
        display:block;

        width:calc(100% - 50px);

        margin:0px auto;
    }

    .list:before{
        position:absolute;
        transform:translate(-10px, 10px);

        content:"";

        width:5px;
        height:5px;

        border-radius:20px;

        background:var(--hoverColor);
    }

    .path{
        color:orange;
    }
</style>


# SOUNDIFY 

<p>This markdown document was created with the <a href="https://marketplace.visualstudio.com/items?itemName=yzhang.markdown-all-in-one">Markdown All in One</a> vscode extension. Please use that extension while reading viewing this document for a good experience.</p>

# LINKS
<a href="#about" style="display:block;">About</a>
<a href="#setup" style="display:block;">How to setup the app</a>
<a href="#planningAndWorkload" style="display:block;">Planning and workload</a>


## About
<div class="section" id="about">
    <p>We are planning on creating a music app where users can add songs or play other user's songs </p>
    <a href="https://github.com/MuhekoNikolas/soundify">Github</a>
    <ol>
        <h3>Team</h3>
        <li>Benjamin</li>
        <li>Nikolas</li>
    </ol>
</div>


## SETTING UP THE APP
<div class="section" id="setup">
Due to the nature of our soundify app, we had to use the <a href="https://phprouter.com/">phprouter</a> external package for managing routes, and I couldn't find any way to have the .htaccess file in any directory other than the root one. So this makes it impossible to have the file structure that was specified in the requirements, in order to use this app: <li>You must replace your htdocs folder with our folder. Just rename you htdocs folder to something different and rename our soundify folder to htdocs. Then open XAMPP control panel and run apache and mysql. Then go to localhost and view the app.</li>
</div>

## Planning and workload
### Frontend Plan
<div class="section" id="planningAndWorkload">
    <p>The app should be able to allow account creation and logging in: 
        <span class="list"> Nikolas will work on the backend and Benjamin will work on the front end, both parties are able to recommend or change functionality of the app on both sides.</span> 
        <span class="list">There is no need to use different html code in both these sites, we can just use the same one and just change it to fit with the page</span>
        <span class="list">Login Path: <span class="path">/login.</span> </span>
        <span class="list">Signup Path: <span class="path">/signup.</span> </span>
    </p>

<p>The app should have a home page: 
    <span class="list"> The home page must contain a section where users can see all songs and all playlists, also top songs of the site.</span> 
    <span class="list">Nikolas works on backend and Benjamin frontend</span>
    <span class="list"> Home Path: <span class="path"> /. </span> </span>
</p>

<p>The app should have an artist page: 
    <span class="list"> The artist page must show an artist's published songs and their playlists just like the home page.</span> 
    <span class="list">Nikolas works on backend and Benjamin frontend</span>
    <span class="list">Artist page Path: <span class="path">/artists/${artistName}>.</span> </span>
</p>

<p>The app should have a Playlist page: 
    <span class="list"> The playlist page must show the songs found in the playlist and also show other playlists on the side.</span> 
    <span class="list">Nikolas works on backend and Benjamin frontend</span>
    <span class="list">Playlist page Path: <span class="path">/playlists/${playlistId}>.</span> </span>
</p>

<p>The app should have song creation page: 
    <span class="list"> The song creation page has a form where the user can upload the song audio, image and the name. This data then gets sent to the backend where we validate and save it.</span> 
    <span class="list">Nikolas works on backend and Benjamin frontend</span>
    <span class="list">Song Creation Page Path: <span class="path">/artists/${artistName}/songs/new>.</span> </span>
</p>

<p>The app should have playlist creation page: 
    <span class="list"> The playlist creation page has a form where the user can upload the playlist image and name. This data then gets sent to the backend where we validate it and create a new playlist for that user.
    </span> 
    <span class="list">Nikolas works on backend and Benjamin frontend</span>
    <span class="list">Playlist Creation Page Path: <span class="path">/artists/${artistName}/playlists/new>.</span> </span>
</p>

</div>

### Backend plan
<div class="section">
    <p>
        Due to the nature of the app, we will need to work with request routing, we will be working with xampp(a server based host) which comes with PHP and mysql. PHP by itself doesnt come with routing packages so we are going to use an external package. I (Nikolas) recommend <a href="https://phprouter.com">phprouter.com</a> because its popular among php devs, lightweight and has easy syntax.
    </p>
    <p>
        I will be using mysql to save and store user data and song ids because by using mysql to save user info: mysql can automatically encrypt the data and make it secure unlike json, also mysql filters duplicates so there cant be two users with the same name/id. I will be using a mysql database called "Soundify" and create the "songs" and "users" table. 
        <p>
        I will be saving the playlists in a json file because I wont be storing any personal data in there, also because saving playlists in a json file is a requirement.
        </p>
        <h3> ./config.php </h3>
        The config.php file has all the information regarding the app, the app's name, description which we import into the other sites(changing the name in config.php will automatically change the name in the pages), config.php also has the database information: <span style="color:red;">You must change the database username and password to your database username and password for the app to function</span>
    </p>
    <h3>App's functionality</h3>
    <p>To see what classes the app uses and their functions please refer to the ./soundifyDrawIO.drawio file. You might need a vscode extension such as <a href="https://marketplace.visualstudio.com/items?itemName=hediet.vscode-drawio">draw.io - Integration</a> to view the file</p>
</div>
This markdown document is completed yet.
