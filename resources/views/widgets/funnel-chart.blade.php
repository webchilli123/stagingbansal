<style>
    .funnel_outer {
        width: 420px;
        float: left;
        position: relative;
        padding: 0 10%;
    }

    .funnel_outer * {
        box-sizing: border-box
    }

    .funnel_outer ul {
        margin: 0;
        padding: 0;
    }

    .funnel_outer ul li {
        float: left;
        position: relative;
        margin: 2px 0;
        height: 50px;
        clear: both;
        text-align: center;
        width: 100%;
        list-style: none;
        position: relative;
    }

    .funnel_outer li span {
        border-top-width: 50px;
        border-top-style: solid;
        border-left: 25px solid transparent;
        border-right: 25px solid transparent;
        height: 0;
        display: inline-block;
        vertical-align: middle;
        position: relative;
    }

    .funnel_outer ul li:hover::after {
        content: attr(data-text);
        position: absolute;
        top: -40px; /* Adjust this value to position the text */
        left: 50%;
        transform: translateX(-50%);
        background: #333;
        color: #fff;
        padding: 5px 10px;
        border-radius: 5px;
        white-space: nowrap;
        z-index: 999; /* Ensure the text is on top of other elements */
    }

    .funnel_step_1 span {
        width: 100%;
        border-top-color: #8080b6;
    }

    .funnel_step_2 span {
        width: calc(100% - 40px);
        border-top-color: #669966
    }

    .funnel_step_3 span {
        width: calc(100% - 80px);
        border-top-color: #a27417
    }

    .funnel_step_7 span {
        width: calc(100% - 130px);
        border-top-color: #ff0000;
    }

    .funnel_outer ul li:last-child span {
        border-left: 0;
        border-right: 0;
        border-top-width: 40px;
    }

    .funnel_outer ul li.not_last span {
        border-left: 5px solid transparent;
        border-right: 5px solid transparent;
        border-top-width: 50px;
    }

    .funnel_outer ul li span p {
        margin-top: -30px;
        color: #fff;
        font-weight: bold;
        text-align: center;
    }
</style>

<div class="funnel_outer">
    <ul>
        <li class="funnel_step_1" data-text="Not Interested, Mature, Follow Ups"><span><p>1</p></span></li>
        <li class="funnel_step_2" data-text="Send Quotation"><span><p>2</p></span></li>
        <li class="funnel_step_3" data-text="Send Performa"><span><p>3</p></span></li>
        <li class="funnel_step_7" data-text="Advance Received"><span><p>4</p></span></li>
    </ul>
</div>


