

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

## About
<div class="section">
    <p>We are planning on creating a music app where users can add songs or play other user's songs </p>
    <a href="https://github.com/MuhekoNikolas/soundify">Github</a>
    <ol>
        <h3>Team</h3>
        <li>Benjamin</li>
        <li>Nikolas</li>
    </ol>
</div>


## Planning and workload
<div class="section">
    <p>The app should be able to allow account creation and logging in: 
        <span class="list"> Nikolas will work on the backend and Benjamin will work on the front end, both parties are able to recommend or change functionality of the app on both sides.</span> 
        <span class="list">There is no need to use different html code in both these sites, we can just use the same one and just change it to fit with the page</span>
        <span class="list">Login Path: <span class="path">/login</span> </span>
        <span class="list">Signup Path: <span class="path">/signup</span> </span>
    </p>

<p>The app should have a home page: 
    <span class="list"> The home page must contain a section where users can see all songs and all playlists, also top songs of the site.</span> 
    <span class="list">Nikolas works on backend and Benjamin frontend</span>
    <span class="list">Home Path: <span class="path">/</span> </span>
</p>

</div>
