<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>WABA Flow Builder</title>
    <style>
        :root {
            --bg: #f4f7fa;
            --panel: #ffffff;
            --line: #dfe6ec;
            --line-soft: #edf1f5;
            --text: #111827;
            --muted: #6f8090;
            --brand: #e31b23;
            --brand-dark: #b9151b;
            --navy: #001a41;
            --dark: #1f2937;
            --green: #24c460;
            --shadow: 0 18px 42px rgba(0, 26, 65, .08);
        }

        * { box-sizing: border-box; }
        body {
            margin: 0;
            background: var(--bg);
            color: var(--text);
            font-family: Inter, ui-sans-serif, system-ui, -apple-system, BlinkMacSystemFont, "Segoe UI", sans-serif;
        }

        button, input, textarea, select { font: inherit; }
        button { cursor: pointer; }

        .topbar {
            position: sticky;
            top: 0;
            z-index: 10;
            height: 96px;
            display: grid;
            grid-template-columns: 240px 1fr 520px;
            align-items: center;
            gap: 24px;
            padding: 0 24px;
            background: #fff;
            border-bottom: 1px solid var(--line-soft);
        }

        .logo {
            display: flex;
            align-items: center;
            gap: 10px;
            width: fit-content;
            font-weight: 900;
            color: var(--navy);
        }

        .logo b {
            display: inline-flex;
            align-items: center;
            min-height: 38px;
            border-radius: 999px;
            background: var(--brand);
            color: #fff;
            font-size: 25px;
            letter-spacing: 0;
            text-transform: lowercase;
            padding: 3px 14px 5px;
        }

        .logo small {
            display: block;
            color: var(--navy);
            font-size: 11px;
            font-weight: 800;
            letter-spacing: .03em;
        }

        .stepper {
            justify-self: center;
            display: grid;
            grid-template-columns: repeat(3, 170px);
            gap: 18px;
            text-align: center;
            color: var(--muted);
            font-weight: 600;
        }

        .step {
            position: relative;
            display: grid;
            gap: 22px;
            border: 0;
            background: transparent;
            color: inherit;
            padding: 0;
        }

        .step::before {
            content: "";
            justify-self: center;
            width: 8px;
            height: 8px;
            border-radius: 50%;
            background: #d8dee5;
            box-shadow: -74px 0 0 -2px #d8dee5, 74px 0 0 -2px #d8dee5;
        }

        .step.active { color: var(--text); }
        .step.active::before { background: var(--brand); }
        .step.done::before {
            content: "✓";
            width: 16px;
            height: 16px;
            display: grid;
            place-items: center;
            background: transparent;
            color: var(--brand);
            font-weight: 900;
            box-shadow: 74px 0 0 -2px var(--brand);
        }

        .builder-actions {
            justify-self: end;
            display: none;
            align-items: center;
            gap: 12px;
        }

        .app[data-step="2"] .builder-actions { display: flex; }
        .app[data-step="2"] .stepper { display: none; }

        .btn {
            min-height: 44px;
            border: 1px solid var(--line);
            border-radius: 8px;
            padding: 0 16px;
            background: white;
            color: var(--text);
            font-weight: 800;
        }

        .btn.dark {
            border-color: var(--brand);
            background: var(--brand);
            color: white;
        }

        .btn.danger {
            border-color: var(--brand);
            color: var(--brand);
        }

        .page {
            min-height: calc(100vh - 96px);
        }

        .form-page {
            max-width: 1260px;
            margin: 0 auto;
            padding: 76px 0 72px;
        }

        .title {
            margin: 0 0 38px;
            font-size: 29px;
            letter-spacing: 0;
            color: var(--navy);
        }

        .card {
            background: var(--panel);
            border: 1px solid var(--line-soft);
            border-radius: 18px;
            padding: 26px;
            box-shadow: var(--shadow);
            margin-bottom: 34px;
        }

        .card h2 {
            margin: 0 0 12px;
            font-size: 22px;
            letter-spacing: 0;
            color: var(--navy);
        }

        .card p {
            margin: 0 0 24px;
            color: #526578;
            line-height: 1.5;
        }

        .field {
            display: grid;
            gap: 12px;
        }

        .field label {
            font-weight: 800;
        }

        .input-shell {
            position: relative;
        }

        .input, .select, .textarea {
            width: 100%;
            min-height: 64px;
            border: 1px solid var(--line);
            border-radius: 8px;
            padding: 0 16px;
            background: white;
            color: var(--text);
        }

        .counter, .suffix {
            position: absolute;
            right: 16px;
            top: 50%;
            transform: translateY(-50%);
            color: var(--muted);
            font-weight: 700;
        }

        .trigger-grid, .two-grid, .message-grid {
            display: grid;
            grid-template-columns: 1fr 1fr;
            gap: 22px;
        }

        .trigger-card {
            display: grid;
            grid-template-columns: 34px 1fr;
            gap: 14px;
            min-height: 256px;
            border: 1px solid var(--line);
            border-radius: 12px;
            padding: 26px 22px;
            background: white;
        }

        .radio {
            width: 20px;
            height: 20px;
            border: 2px solid #7b8b9a;
            border-radius: 50%;
            margin-top: 8px;
        }

        .trigger-card.active .radio {
            border-color: var(--brand);
            box-shadow: inset 0 0 0 5px white;
            background: var(--brand);
        }

        .trigger-card.active {
            border-color: rgba(227, 27, 35, .55);
            box-shadow: 0 0 0 3px rgba(227, 27, 35, .08);
        }

        .trigger-card strong {
            display: block;
            margin-bottom: 6px;
            font-size: 18px;
        }

        .trigger-card span {
            display: block;
            color: #526578;
            margin-bottom: 22px;
        }

        .example {
            min-height: 142px;
            display: grid;
            grid-template-columns: 1fr 220px;
            gap: 16px;
            border: 1px solid var(--line);
            border-radius: 8px;
            background: #f8fafc;
            padding: 16px;
            overflow: hidden;
        }

        .example h3 {
            margin: 0 0 16px;
            color: #8b9bab;
            font-size: 16px;
        }

        .example p {
            margin: 0;
            color: #8b9bab;
        }

        .phone-preview {
            min-height: 126px;
            border-radius: 16px;
            padding: 14px;
            background-color: #eee8df;
            background-image:
                radial-gradient(circle at 16px 18px, rgba(120, 110, 100, .08) 0 5px, transparent 6px),
                radial-gradient(circle at 68px 48px, rgba(120, 110, 100, .08) 0 10px, transparent 11px),
                radial-gradient(circle at 145px 24px, rgba(120, 110, 100, .08) 0 7px, transparent 8px);
            background-size: 120px 86px;
        }

        .bubble {
            max-width: 76%;
            width: fit-content;
            border-radius: 4px;
            padding: 10px 12px;
            background: white;
            color: #556575;
            font-size: 12px;
            line-height: 1.4;
            box-shadow: 0 8px 16px rgba(0,0,0,.05);
        }

        .bubble.out { margin-left: auto; background: #dbffd4; }
        .bubble time {
            display: block;
            margin-top: 6px;
            text-align: right;
            color: #8b9bab;
            font-size: 10px;
        }

        .dates {
            display: grid;
            grid-template-columns: 1fr 1fr;
            gap: 28px;
        }

        .editor-card {
            border: 1px solid var(--line-soft);
            border-radius: 18px;
            padding: 26px;
            background: white;
            box-shadow: 0 16px 36px rgba(23, 35, 50, .05);
        }

        .toolbar {
            display: flex;
            align-items: center;
            gap: 18px;
            min-height: 48px;
            border: 1px solid var(--line);
            border-radius: 8px 8px 0 0;
            padding: 8px 12px;
        }

        .toolbar select {
            min-width: 138px;
            border: 1px solid var(--line);
            border-radius: 7px;
            padding: 8px 10px;
            background: white;
        }

        .tool {
            border: 0;
            background: transparent;
            color: var(--dark);
            font-size: 18px;
            font-weight: 800;
        }

        .textarea {
            min-height: 210px;
            border-radius: 0 0 8px 8px;
            border-top: 0;
            padding: 18px;
            resize: vertical;
            line-height: 1.5;
        }

        .footer-card {
            display: flex;
            align-items: center;
            justify-content: flex-end;
            gap: 18px;
        }

        .builder {
            position: relative;
            height: calc(100vh - 96px);
            overflow: auto;
            background:
                radial-gradient(circle, #cfd7df 1px, transparent 1px) 0 0 / 28px 28px,
                #fbfcfd;
        }

        .flow {
            position: absolute;
            left: 50%;
            top: 128px;
            width: min(460px, calc(100vw - 48px));
            transform: translateX(-50%);
            display: grid;
            justify-items: center;
        }

        .start-node {
            width: 100%;
            min-height: 64px;
            display: grid;
            place-items: center;
            border-radius: 18px;
            background: var(--brand);
            color: white;
            font-size: 25px;
            font-weight: 900;
        }

        .line {
            width: 2px;
            height: 58px;
            background: #111827;
        }

        .response-node {
            width: 100%;
            border: 1px solid var(--line-soft);
            border-radius: 18px;
            padding: 28px 22px 20px;
            background: #f6f8fa;
            box-shadow: var(--shadow);
        }

        .response-node h2 {
            margin: 0 0 16px;
            font-size: 24px;
        }

        .keyword-box {
            min-height: 74px;
            display: flex;
            align-items: center;
            border-radius: 14px;
            background: white;
            padding: 0 14px;
            font-size: 22px;
        }

        .bot-nodes {
            width: 100%;
            display: grid;
            justify-items: center;
        }

        .bot-node {
            width: 100%;
            border: 1px solid var(--line-soft);
            border-radius: 18px;
            padding: 22px;
            background: white;
            box-shadow: var(--shadow);
        }

        .bot-node header {
            display: flex;
            align-items: center;
            justify-content: space-between;
            gap: 12px;
            margin-bottom: 14px;
        }

        .bot-node h2 {
            margin: 0;
            color: var(--navy);
            font-size: 21px;
        }

        .node-pill {
            display: inline-flex;
            align-items: center;
            min-height: 28px;
            border-radius: 999px;
            background: rgba(227, 27, 35, .08);
            color: var(--brand);
            padding: 4px 10px;
            font-size: 12px;
            font-weight: 900;
        }

        .node-message {
            min-height: 58px;
            display: flex;
            align-items: center;
            border-radius: 12px;
            background: #f7f9fb;
            color: #526578;
            padding: 14px;
            line-height: 1.45;
        }

        .remove-node {
            width: 34px;
            height: 34px;
            border: 1px solid var(--line);
            border-radius: 8px;
            background: white;
            color: var(--brand);
            font-size: 20px;
            font-weight: 900;
        }

        .add-node {
            position: relative;
            width: 100%;
        }

        .add-response {
            width: 100%;
            min-height: 70px;
            display: flex;
            align-items: center;
            justify-content: center;
            gap: 18px;
            border: 1px solid var(--line-soft);
            border-radius: 18px;
            background: #f6f8fa;
            color: var(--text);
            font-size: 24px;
            font-weight: 900;
            box-shadow: 0 10px 26px rgba(23, 35, 50, .04);
        }

        .plus {
            font-size: 38px;
            line-height: 1;
        }

        .menu {
            position: absolute;
            left: calc(100% - 28px);
            top: 0;
            width: 180px;
            display: none;
            grid-template-columns: 1fr;
            border-radius: 10px;
            background: white;
            box-shadow: 0 18px 42px rgba(23, 35, 50, .15);
            overflow: hidden;
        }

        .menu.open { display: grid; }
        .menu button {
            min-height: 43px;
            border: 0;
            background: white;
            text-align: left;
            padding: 0 14px;
            color: #283441;
        }

        .menu button:hover { background: #eefbff; }

        .zoom {
            position: absolute;
            left: 16px;
            bottom: 20px;
            display: grid;
            width: 32px;
            border: 1px solid var(--line);
            background: white;
        }

        .zoom button {
            height: 32px;
            border: 0;
            border-bottom: 1px solid var(--line);
            background: white;
            font-size: 20px;
            font-weight: 800;
        }

        .zoom button:last-child { border-bottom: 0; }

        .minimap {
            position: absolute;
            right: 16px;
            bottom: 20px;
            width: 224px;
            height: 168px;
            border: 8px solid #dedede;
            background: white;
        }

        .minimap::before {
            content: "";
            position: absolute;
            left: 28px;
            top: 56px;
            width: 92px;
            height: 46px;
            background: #e2e5e9;
        }

        .screen { display: none; }
        .screen.active { display: block; }

        @media (max-width: 1100px) {
            .topbar {
                height: auto;
                grid-template-columns: 1fr;
                padding: 18px;
            }

            .stepper {
                justify-self: stretch;
                grid-template-columns: repeat(3, 1fr);
            }

            .builder-actions {
                justify-self: stretch;
                flex-wrap: wrap;
            }
        }

        @media (max-width: 760px) {
            .form-page { padding: 34px 14px; }
            .card { padding: 18px; border-radius: 12px; }
            .trigger-grid, .two-grid, .message-grid, .dates {
                grid-template-columns: 1fr;
            }

            .example { grid-template-columns: 1fr; }
            .stepper { gap: 8px; font-size: 12px; }
            .step::before { box-shadow: none; }
            .builder-actions .btn { flex: 1 1 150px; }
            .minimap { display: none; }
            .menu {
                left: auto;
                right: 0;
                top: 74px;
            }
        }
    </style>
</head>
<body>
<main class="app" id="app" data-step="0">
    <header class="topbar">
        <div class="logo">
            <b>myads</b>
            <small>TELKOMSEL</small>
        </div>

        <nav class="stepper" aria-label="Flow setup progress">
            <button class="step active" type="button" data-step-target="0">Initial Setup</button>
            <button class="step" type="button" data-step-target="1">Time Out & Fallback</button>
            <button class="step" type="button" data-step-target="2">Design Flow Builder</button>
        </nav>

        <div class="builder-actions">
            <button class="btn" type="button" data-step-target="1">Edit Period</button>
            <button class="btn danger" type="button" data-action="discard">Discard Changes</button>
            <button class="btn" type="button" data-action="draft">Save as Draft</button>
            <button class="btn dark" type="button" data-action="publish">Publish Edited Flow</button>
        </div>
    </header>

    <section class="screen active" data-screen="0">
        <div class="form-page">
            <h1 class="title">Add New Flow Builder</h1>

            <section class="card">
                <h2>Project name and language</h2>
                <div class="field">
                    <div class="input-shell">
                        <input class="input" id="flowName" maxlength="255" placeholder="Name rule based project">
                        <span class="counter" id="nameCounter">0/255</span>
                    </div>
                </div>
            </section>

            <section class="card">
                <h2>Choose your trigger</h2>
                <p>Pilih cara pelanggan masuk ke flow MyAds untuk campaign WhatsApp Business.</p>
                <div class="trigger-grid">
                    <button class="trigger-card active" type="button" data-trigger="Inbound">
                        <span class="radio"></span>
                        <span>
                            <strong>Inbound</strong>
                            <span>Started by your customers</span>
                            <span class="example">
                                <span>
                                    <h3>Example Output</h3>
                                    <p>Percakapan dimulai ketika pelanggan mengirim pesan ke brand Anda.</p>
                                </span>
                                <span class="phone-preview">
                                    <span class="bubble out">Halo Admin, tanya dong<time>09:25</time></span>
                                    <span class="bubble">Terima kasih telah percaya dengan FTA.<time>09:25</time></span>
                                </span>
                            </span>
                        </span>
                    </button>

                    <button class="trigger-card" type="button" data-trigger="Outbound">
                        <span class="radio"></span>
                        <span>
                            <strong>Outbound</strong>
                            <span>Started by blast marketing/utility message template.</span>
                            <span class="example">
                                <span>
                                    <h3>Example Output</h3>
                                    <p>Kirim campaign lebih dulu memakai template WhatsApp yang sudah disetujui.</p>
                                </span>
                                <span class="phone-preview">
                                    <span class="bubble">Gratis 30 hari latihan fisik. Coba sekarang.<time>09:25</time></span>
                                </span>
                            </span>
                        </span>
                    </button>
                </div>
            </section>

            <section class="card">
                <h2>Set Validity Period</h2>
                <p>Tentukan periode campaign MyAds aktif dan bisa menerima response pelanggan.</p>
                <div class="dates">
                    <label class="field">
                        <span>Start Date</span>
                        <input class="input" type="date" id="startDate">
                    </label>
                    <label class="field">
                        <span>End Date</span>
                        <input class="input" type="date" id="endDate">
                    </label>
                </div>
            </section>

            <section class="card footer-card">
                <button class="btn" type="button">Cancel</button>
                <button class="btn dark" type="button" data-next>Next</button>
            </section>
        </div>
    </section>

    <section class="screen" data-screen="1">
        <div class="form-page">
            <h1 class="title">Add New Flow Builder</h1>

            <section class="card">
                <h2>Define Your Timeout</h2>
                <p>Pilih durasi tunggu sebelum pelanggan diarahkan ke fallback message.</p>
                <div class="two-grid">
                    <label class="field">
                        <span>Choose Duration</span>
                        <select class="select" id="duration">
                            <option value="">Choose Duration</option>
                            <option value="15">15 minutes</option>
                            <option value="30">30 minutes</option>
                            <option value="60">60 minutes</option>
                        </select>
                    </label>
                    <label class="field">
                        <span>Custom Duration (minutes)</span>
                        <span class="input-shell">
                            <input class="input" id="customDuration" type="number" min="1">
                            <span class="suffix">Minute</span>
                        </span>
                    </label>
                </div>
            </section>

            <section class="card">
                <h2>Set Your Message Timeout</h2>
                <p>Tulis pesan otomatis saat pelanggan tidak membalas dalam durasi yang ditentukan.</p>
                <div class="message-grid">
                    <div class="editor-card">
                        <p>Message Content</p>
                        <div class="toolbar">
                            <select aria-label="Paragraph style"><option>Paragraph</option></select>
                            <button class="tool" type="button">B</button>
                            <button class="tool" type="button"><i>I</i></button>
                            <button class="tool" type="button"><u>U</u></button>
                            <button class="tool" type="button"><s>S</s></button>
                            <button class="tool" type="button">☷</button>
                            <button class="tool" type="button">☰</button>
                        </div>
                        <textarea class="textarea" id="timeoutMessage" maxlength="1024" placeholder="Write something awesome, example Terima kasih telah berbicara dengan tim dukungan kami! Bila memiliki pertanyaan lain, silakan menghubungi kami kembali."></textarea>
                    </div>
                    <div class="editor-card">
                        <p>Message Preview</p>
                        <div class="phone-preview">
                            <div class="bubble" id="timeoutPreview">Your text will appear here..<time>09:25</time></div>
                        </div>
                    </div>
                </div>
            </section>

            <section class="card">
                <h2>Set Your Starting Agent Response</h2>
                <p>Write the starting agent response message sent to a customer</p>
                <div class="message-grid">
                    <div class="editor-card">
                        <p>Write the starting agent response message sent to a customer</p>
                        <div class="toolbar">
                            <select aria-label="Paragraph style"><option>Paragraph</option></select>
                            <button class="tool" type="button">B</button>
                            <button class="tool" type="button"><i>I</i></button>
                            <button class="tool" type="button"><u>U</u></button>
                            <button class="tool" type="button"><s>S</s></button>
                            <button class="tool" type="button">☷</button>
                        </div>
                        <textarea class="textarea" id="startAgent" maxlength="1024" placeholder="Write something awesome..."></textarea>
                    </div>
                    <div class="editor-card">
                        <p>Message Preview</p>
                        <div class="phone-preview">
                            <div class="bubble" id="startAgentPreview">Your text will appear here..<time>09:25</time></div>
                        </div>
                    </div>
                </div>
            </section>

            <section class="card">
                <h2>Set Your Resolve Agent Response</h2>
                <p>Write the Resolve agent response message sent to a customer</p>
                <div class="message-grid">
                    <div class="editor-card">
                        <p>Write the Resolve agent response message sent to a customer</p>
                        <div class="toolbar">
                            <select aria-label="Paragraph style"><option>Paragraph</option></select>
                            <button class="tool" type="button">B</button>
                            <button class="tool" type="button"><i>I</i></button>
                            <button class="tool" type="button"><u>U</u></button>
                            <button class="tool" type="button"><s>S</s></button>
                            <button class="tool" type="button">☷</button>
                        </div>
                        <textarea class="textarea" id="resolveAgent" maxlength="1024" placeholder="Write something awesome..."></textarea>
                    </div>
                    <div class="editor-card">
                        <p>Message Preview</p>
                        <div class="phone-preview">
                            <div class="bubble" id="resolveAgentPreview">Your text will appear here..<time>09:25</time></div>
                        </div>
                    </div>
                </div>
            </section>

            <section class="card footer-card">
                <button class="btn" type="button">Cancel</button>
                <button class="btn" type="button" data-prev>Back</button>
                <button class="btn dark" type="button" data-next>Next</button>
            </section>
        </div>
    </section>

    <section class="screen" data-screen="2">
        <div class="builder">
            <div class="flow">
                <div class="start-node">Start</div>
                <div class="line"></div>
                <div class="response-node">
                    <h2>User Response</h2>
                    <div class="keyword-box">Any Keyword Send</div>
                </div>
                <div class="line"></div>
                <div class="bot-nodes" id="botNodes"></div>
                <div class="add-node">
                    <button class="add-response" type="button" data-action="open-menu"><span class="plus">+</span> Tambah Flow Baru</button>
                    <div class="menu" id="responseMenu">
                        <button type="button" data-node="Text Messages">Text Messages</button>
                        <button type="button" data-node="Button">Button</button>
                        <button type="button" data-node="List">List</button>
                        <button type="button" data-node="AI Response">AI Response</button>
                        <button type="button" data-node="Agent Response">Agent Response</button>
                        <button type="button" data-node="Reuse Bot Response">Reuse Bot Response</button>
                    </div>
                </div>
            </div>

            <div class="zoom" aria-label="Canvas controls">
                <button type="button">+</button>
                <button type="button">−</button>
                <button type="button">⌗</button>
                <button type="button">▣</button>
            </div>
            <div class="minimap" aria-hidden="true"></div>
        </div>
    </section>
</main>

<script>
    const app = document.getElementById('app');
    const steps = [...document.querySelectorAll('.step')];
    const screens = [...document.querySelectorAll('.screen')];
    const responseMenu = document.getElementById('responseMenu');
    const botNodes = document.getElementById('botNodes');
    const csrf = document.querySelector('meta[name="csrf-token"]').content;
    let currentStep = 0;
    let selectedTrigger = 'Inbound';
    let flowNodes = [];

    function setStep(index) {
        currentStep = Math.max(0, Math.min(2, index));
        app.dataset.step = String(currentStep);

        screens.forEach((screen) => {
            screen.classList.toggle('active', Number(screen.dataset.screen) === currentStep);
        });

        steps.forEach((step, stepIndex) => {
            step.classList.toggle('active', stepIndex === currentStep);
            step.classList.toggle('done', stepIndex < currentStep);
        });
    }

    function plainText(value) {
        return value.trim() || 'Your text will appear here..';
    }

    function bindPreview(textareaId, previewId) {
        const textarea = document.getElementById(textareaId);
        const preview = document.getElementById(previewId);
        textarea.addEventListener('input', () => {
            preview.innerHTML = `${escapeHtml(plainText(textarea.value))}<time>09:25</time>`;
        });
    }

    function escapeHtml(value) {
        return String(value)
            .replaceAll('&', '&amp;')
            .replaceAll('<', '&lt;')
            .replaceAll('>', '&gt;')
            .replaceAll('"', '&quot;')
            .replaceAll("'", '&#039;');
    }

    function defaultMessage(type) {
        const messages = {
            'Text Messages': 'Kirim pesan teks otomatis ke pelanggan.',
            Button: 'Tampilkan pilihan tombol cepat untuk pelanggan.',
            List: 'Tampilkan daftar opsi yang bisa dipilih pelanggan.',
            'AI Response': 'Gunakan AI untuk membalas sesuai konteks percakapan.',
            'Agent Response': 'Teruskan percakapan ke agent.',
            'Reuse Bot Response': 'Gunakan ulang response bot yang sudah tersedia.',
        };

        return messages[type] || 'Flow baru siap dikonfigurasi.';
    }

    function renderFlowNodes() {
        botNodes.innerHTML = flowNodes.map((node, index) => `
            <div class="bot-node" data-flow-node="${node.id}">
                <header>
                    <div>
                        <span class="node-pill">Flow ${index + 1}</span>
                        <h2>${escapeHtml(node.title)}</h2>
                    </div>
                    <button class="remove-node" type="button" title="Hapus flow" data-remove-node="${node.id}">×</button>
                </header>
                <div class="node-message">${escapeHtml(node.message)}</div>
            </div>
            <div class="line"></div>
        `).join('');
    }

    function addFlowNode(type) {
        flowNodes.push({
            id: `bot-${Date.now().toString(36)}-${flowNodes.length + 1}`,
            type: type.toLowerCase().replaceAll(' ', '_'),
            title: type,
            message: defaultMessage(type),
        });
        renderFlowNodes();
    }

    async function submitFlow(status) {
        const flow = {
            name: document.getElementById('flowName').value || 'Untitled Flow',
            trigger: selectedTrigger,
            status,
            nodes: [
                { id: 'start', type: 'start', title: 'Start', message: '' },
                { id: 'user-response', type: 'user_response', title: 'User Response', message: 'Any Keyword Send' },
                ...flowNodes,
            ],
        };

        await fetch('/flows/preview', {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
                'Accept': 'application/json',
                'X-CSRF-TOKEN': csrf,
            },
            body: JSON.stringify(flow),
        });

        alert(status === 'published' ? 'Flow siap dipublish.' : 'Flow disimpan sebagai draft.');
    }

    document.querySelectorAll('[data-step-target]').forEach((button) => {
        button.addEventListener('click', () => setStep(Number(button.dataset.stepTarget)));
    });

    document.querySelectorAll('[data-next]').forEach((button) => {
        button.addEventListener('click', () => setStep(currentStep + 1));
    });

    document.querySelectorAll('[data-prev]').forEach((button) => {
        button.addEventListener('click', () => setStep(currentStep - 1));
    });

    document.querySelectorAll('[data-trigger]').forEach((button) => {
        button.addEventListener('click', () => {
            selectedTrigger = button.dataset.trigger;
            document.querySelectorAll('[data-trigger]').forEach((item) => item.classList.remove('active'));
            button.classList.add('active');
        });
    });

    document.getElementById('flowName').addEventListener('input', (event) => {
        document.getElementById('nameCounter').textContent = `${event.target.value.length}/255`;
    });

    document.querySelector('[data-action="open-menu"]').addEventListener('click', () => {
        responseMenu.classList.toggle('open');
    });

    responseMenu.querySelectorAll('button').forEach((button) => {
        button.addEventListener('click', () => {
            addFlowNode(button.dataset.node);
            responseMenu.classList.remove('open');
        });
    });

    botNodes.addEventListener('click', (event) => {
        const removeButton = event.target.closest('[data-remove-node]');
        if (!removeButton) return;

        flowNodes = flowNodes.filter((node) => node.id !== removeButton.dataset.removeNode);
        renderFlowNodes();
    });

    document.querySelector('[data-action="discard"]').addEventListener('click', () => setStep(0));
    document.querySelector('[data-action="draft"]').addEventListener('click', () => submitFlow('draft'));
    document.querySelector('[data-action="publish"]').addEventListener('click', () => submitFlow('published'));

    bindPreview('timeoutMessage', 'timeoutPreview');
    bindPreview('startAgent', 'startAgentPreview');
    bindPreview('resolveAgent', 'resolveAgentPreview');
    setStep(0);
</script>
</body>
</html>
