# Developer brief: ECD and Academic & Career pillar page redesign

| | |
|---|---|
| **Client** | Fairheads Benefit Services (contact: Anthea Thorne, Marketing Manager) |
| **Site** | https://khanyanathi.launchweb.co.za/ (WordPress, **Breakdance + ACF PRO**, xneelo `dedi1138`) |
| **Change request** | Estimate approved by Anthea on **13 Oct 2026** |
| **Owner / reviewer** | Developer: Axel · Reviewer: Khaka |
| **Client review date** | **Tue 27 Oct 2026.** Anthea wants to see the progress so far even without the caregiver videos |
| **ClickUp** | [ECD task](https://app.clickup.com/t/12487v2dtcu) · [Academic & Career task](https://app.clickup.com/t/12487v2dtct) |
| **Local setup** | [docs/local-setup.md](./local-setup.md) |

---

## 0. Before you start (15 min)

**Why this matters:** the audience is youth, single mothers and grandmothers in rural and semi-urban South Africa, mostly on cheap phones with little data. So every decision is **mobile-first and lightweight**. Nothing should load until someone taps it.

**Words to use** (the client renamed things):
- "Areas of Support", not "pillars"
- "Academic and Career Support", not "Educational Support" (slug: `/academic-and-career-support/`)
- "Helpful information", not "What you'll find here"

**Read these, in this order:**
1. 📐 **Wireframe + full ACF spec (build from this):** [ClickUp artifact v5](https://app.clickup.com/90151870542/artifact/2kyqgr2e-34915?version=5). Its *Data model* tab tells you what to create, what to edit and what to clean up.
2. 🖼️ **What the client approved:** *Khanya-Nathi-Proposed-Page-Layouts.pdf* (attached to both ClickUp tasks). The finished pages must match it.
3. 📘 **House style for copy:** [Website Build Guide](https://docs.google.com/document/d/13FbQv-s51udNzOOf0z72wO01DLP-KoZTKBEAmnOxfYQ/edit). Blue text is copy, red text is instructions.
4. 🎨 Brand colours: [Colour scheme doc](https://drive.google.com/file/d/1lGCyxvnpTRQxkrSa_iToFWYn6pIxboex/view). ECD is orange and Academic is blue.

**Check these before building** ✅
- [ ] **ACF PRO licence is active on staging.** The repeaters and options page won't work without it. This is a blocker, so tell Khaka immediately if it's not.
- [ ] You can log in to staging wp-admin, and KonsoleH works. The FTP passwords were reset on 19 Oct.
- [ ] Take a **KonsoleH backup of staging** before you change anything.

---

## Part A: ECD page (`/ecd/`)

### A1. Data model: create the structure in ACF
**Do:**
1. Create the **ROOTS Resource** post type and the **ROOTS Area** taxonomy. Its 5 terms are R, O-Learn, O-Routine, T and S.
2. Create the **Help Numbers** options page, with a repeater for name, number and note.
3. Add fields to the ECD page for the **Caregiver Guide** (file field), **Kids routine guide** (file field) and **5 ROOTS cards** (title, meaning, video URL, anchor link).
4. Use the exact field names, types and return formats from the wireframe spec.

**Docs for this step:** Wireframe → *Data model* tab
**Done when:** the field groups appear in `wp-content/acf-json/` and are committed to the branch (see local-setup).

### A2. Import the content
**Do:**
1. Import **53 resources** from `roots-resources-import.csv` into ROOTS Resource. Use **WP All Import**, or ask Khaka for a WP-CLI script.
2. Import **14 help contacts** from `help-contacts-import.csv` into the Help Numbers options page.
3. The **11 resources marked "Fairheads to supply"** (Feelings Wheel, Boundaries and the others) should show a **"Coming soon"** label, not a broken link.

**Docs for this step:** `roots-resources-import.csv`, `help-contacts-import.csv`, and *ECD - Resource list- updated Oct 2026.xlsx* (the original, for reference only) — all attached to the [ECD task](https://app.clickup.com/t/12487v2dtcu). The xlsx is also [on Drive](https://drive.google.com/file/d/187DAQv3XHdg9eICkR8J93FFkcjEhw-BM/view).
**Phone numbers:** FAMSA, Childline and Child Welfare are different on the two tabs of the spreadsheet. **Use the help-contacts CSV for now.** Khaka is confirming with Anthea.
**Done when:** you have 53 published resources across 5 areas and 14 help numbers on the options page.

### A3. Build the reusable Breakdance blocks
**Do:** build these as Global Blocks so the Academic page can reuse them:
- **Hero with file buttons:** "Open the guide" (new tab) and "Download to print" (download attribute)
- **ROOTS / feature card:** title, meaning and optional video. **If the field is empty, hide the whole wrapper** with Breakdance conditions.
- **Tap-to-call button list:** uses `tel:` links, and the numbers must be big enough to tap with a thumb
- **Lazy YouTube embed:** shows a thumbnail first and loads the iframe only on tap (`youtube-nocookie.com`)

**Done when:** each block renders correctly whether its field is filled or empty.

### A4. Put the ECD page together
Section order:
1. Introduction: **leave as is**
2. **Caregiver Guide hero.** This is the first thing people see. Use **Caregiver Guide v2**.
3. Who is this page for?: **leave as is**
4. **ROOTS: what every child needs.** 5 cards, one per area:
   | Card | Meaning (exact text) |
   |---|---|
   | R: Relationships | "I am loved. I belong." |
   | O: Opportunities to Learn | "I can discover and grow." |
   | O: Order / Routine | "I know what to expect." **The Kids routine guide download goes on this card** |
   | T: Talk, Tell Stories, Teach | "I can express myself and make sense of my world." |
   | S: Safety, Hope & Self-Belief | "I am safe. Tomorrow can be better." |
   Each card links to its anchor on the resource page (`/ecd/resources/#relationships` and so on). **The video slots stay empty for now.** The 5 caregiver videos are still being made.
5. **Need help now?** Police / GBV 10111 · Ambulance 10177 · Childline 0800 055 555, plus a link to the full list
6. Helpful information: the existing ECD topics, **restyled to match the other Areas of Support**

**Docs for this step:** *ECD - Caregiver Guide v2.pdf* and *ECD - Kids routine guide.pdf* (both on the ClickUp task) · Layouts PDF (ECD pages)

### A5. ROOTS resource list page (new): `/ecd/resources/`
**Do:**
1. Short intro, a **"Download the full list (PDF)"** button and a link back to the guide.
   - **Hide the download button for now.** Anthea hasn't sent the PDF yet.
2. **Jump links** to the 5 ROOTS areas at the top, so nobody has to scroll the whole list on a phone.
3. Resources grouped by area. External links open in a new tab, and phone numbers use `tel:`.
4. All 14 help numbers as tap-to-call buttons.
5. 🔒 **Lock the slug `/ecd/resources/`.** It is printed on the guide's QR code. Never change it after launch.

### A6. Clean up while you're in there
- [ ] Delete the **old duplicate ECD post type** from the first build. It's why ECD looks different from the other areas.
- [ ] Delete the lorem ipsum resource posts and the **two stray pages**
- [ ] Fix the slug `school-readinesss` → `school-readiness`, and **add a 301 redirect** in Rank Math
- [ ] Remove the **WhatsApp button**. The client does not want WhatsApp

---

## Part B: Academic and Career Support page (`/academic-and-career-support/`)

### B1. Fields
Add video and file fields to the existing academic resource post type, and reuse the A3 blocks. Use the wireframe's *Academic & Career* tab.

### B2. Put the page together
1. Introduction: **leave as is**
2. **"Powered by CareerXplora" panel.** This is the main focus. It has their logo, the *What is CareerXplora?* video, and two buttons:
   - **Explore careers** → https://careerxplora.com/app-landing
   - **Chat to an advisor** → their chat service (the link is on the chat flyer)
3. Who is this page for?: **leave as is**
4. **Why use CareerXplora?** Retype the points from the graphic as **real page text**. Don't use the image, because it won't read on mobile.
5. **How it works.** Three short steps that **we write** from the presentation. The presentation is background, so don't publish it.
6. **Videos.** CareerXplora videos in the main row and the social clips below, all lazy-loaded.
7. Helpful information: the existing topics, restyled

**Branding rule:** CareerXplora is black and white with its own look. Keep their look **inside the partner panel only**. Everything around it stays Khanya Nathi Academic blue.

### B3. Videos (updated 27 Oct)
The plan changed: **Fairheads now hosts the videos on their own YouTube channel (unlisted) and sends us the links.** Do **not** upload them to a Launch account. Anthea sent 4 links today:
- https://youtube.com/shorts/ZHoTCJi7UHY
- https://youtube.com/shorts/IP5RfFD6MWA
- https://youtube.com/shorts/xvJOhpl6-ZA
- https://youtube.com/shorts/ZLzjT5znS8o

Match each link to its video using the WeTransfer zips on the [Academic task](https://app.clickup.com/t/12487v2dtct). If the 4 social clips aren't among these links, keep their slots hidden until they arrive. Shorts are vertical, so use a 9:16 frame.

**Docs for this step:** *CareerXplora flyer (website).png*, *CareerXplora flyer (chat service).jpg*, *Why use CareerXplora.jpg* and *CareerXplora presentation.pdf* (all on the ClickUp task) · Layouts PDF pages 8–9

---

## C. Quality checks before you say "done"
- [ ] **Mobile first.** Test at 360px wide in DevTools and on a real Android phone if you have one.
- [ ] **Speed.** PageSpeed mobile score ≥ 80, and no YouTube iframes in the initial HTML.
- [ ] **Empty states.** Empty fields hide cleanly, with no empty boxes or "undefined".
- [ ] Every `tel:` link dials and every external link opens in a new tab.
- [ ] No lorem ipsum anywhere: search wp-admin for "lorem".
- [ ] Clear the WP Rocket cache on staging after publishing.
- [ ] The ACF JSON is committed to the branch.

## D. Hand-off
Post the **staging links** for `/ecd/`, `/ecd/resources/` and `/academic-and-career-support/` as a comment on each ClickUp task, with:
1. what's done
2. what's hidden while it waits on the client (see below)
3. anything you weren't sure about

## Still waiting on Anthea: build around these, don't wait for them
| Item | What to do now |
|---|---|
| Final Caregiver Guide | Use v2. It's a file-field swap later |
| PDF of the resource list | Hide the download button |
| 11 "Khanya Nathi Resource" PDFs | Show "Coming soon" |
| 5 ROOTS caregiver videos | Hide the empty video slots |
| Correct FAMSA / Childline / Child Welfare numbers | Use the CSV numbers. Khaka is confirming |
| QR code on the guide | Lock `/ecd/resources/`. Khaka sends Anthea the final URL once the domain is live |

**Blocked, or something unclear?** Message Khaka straight away. Don't guess on anything the client will see.
