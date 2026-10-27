# Handover brief for a new Claude session (Khanya Nathi)

You're picking up from an earlier session. Read this fully before doing anything.

## Who and what
- I'm **Khaka Makaba**, Head of Web Development at **Launch Digital**.
- **Client:** Fairheads Benefit Services. **Contact:** Anthea Thorne, Marketing Manager (athorne@fairheads.com). **Fairheads IT / server:** Johan Marais. **Developer:** Axel Lukanda (axel@launchdigital.agency).
- **Project:** Khanya Nathi ("Shine with us"), Fairheads' social impact website.
- **Audience:** youth, guardians, single mothers and grandmothers in rural and semi-urban South Africa, on cheap phones with very little data. **Mobile-first and lightweight is a requirement.**
- **Staging:** https://khanyanathi.launchweb.co.za/, on xneelo dedicated server `dedi1138.jnb2.host-h.net`, managed through KonsoleH. **Live domain:** khanyanathi.co.za. It's not live yet; DNS still needs pointing with Johan.
- **Stack:** WordPress · **Breakdance** (page builder, *not* Elementor) · **ACF PRO** · Rank Math · WP Rocket · Gravity Forms · Solid Security · NinjaFirewall.
- **Words to use:**
  - "Areas of Support", not "pillars"
  - "Academic and Career Support", not "Educational Support" (slug `/academic-and-career-support/`)
  - "Helpful information", not "What you'll find here"
  - "You will learn more about", not "What this topic covers"
  - "Fairheads Benefit Services", not just "Fairheads", in eyebrows
  - **No WhatsApp** anywhere

## Where I am now
- I've set up a **LocalWP** copy of the site on my computer. Help me build there.
- **Git repo:** `khakaadmin/khanyanathi`, branch `claude/laughing-lamport-krt6s3`. It holds:
  - `wp-content/mu-plugins/khanyanathi-core.php`: saves ACF field groups as JSON in `wp-content/acf-json`
  - `docs/` with the setup notes and this brief
- **Rules for local work:**
  - Breakdance layouts live in the **database**, so Git can't carry them. Git carries code and ACF JSON only.
  - **Staging is the source of truth.** Never push a local database over staging.
  - Move ACF changes to staging by uploading the `acf-json` files, then running ACF → Sync.
  - Final Breakdance layouts are built on staging, or copied with Breakdance copy/paste or Global Blocks.
  - Turn off WP Rocket, NinjaFirewall and Solid Security locally.

## The current job: change request approved 13 Oct
Two pillar pages are being redesigned, plus one new page. **The review was promised to Anthea for Tue 27 Oct**, and on 27 Oct she asked to see the site as it stands.

### What Anthea approved (source of truth)
My email of 12 Oct, which she approved on 13 Oct:
- **ECD page (`/ecd/`, Page 271):**
  - The Caregiver Guide is the main focus near the top, with **Open the guide** (PDF in a new tab) and **Download to print**.
  - Then **"ROOTS: what every child needs"**: 5 cards with each area's meaning, a space for its caregiver video, and a link to that area's resources. The Kids routine guide goes on the Order / Routine card.
  - Then **"Need help now?"**: tap-to-call for Police / GBV, Ambulance and Childline, plus a link to the full list.
  - The existing ECD topics stay below.
- **New ROOTS resource list page (`/ecd/resources/`, child of ECD):**
  - All organisations and links from her spreadsheet, grouped by the 5 ROOTS areas, with jump links
  - All the Need Help numbers as tap-to-call
  - **Download the full list (PDF)**, plus a link back to the guide
  - **The slug must never change.** It goes on the guide's QR code.
- **Academic and Career Support page (Page 515):**
  - **CareerXplora** is the main focus at the top: a "Powered by CareerXplora" panel with the "What is CareerXplora?" video, **Explore careers** (https://careerxplora.com/app-landing) and **Chat to an advisor**.
  - Then **Why use CareerXplora?** and **How it works**, which we write from her flyers and presentation.
  - Then 4 learner videos and 4 social clips that load only on tap.
  - The existing 6 topics stay below.
  - CareerXplora's black-and-white branding stays **inside the panel only**.
- Both pages must let Fairheads swap the guide, add videos and update the list **without us**.
- ⚠️ A "Proposed Page Layouts" PDF exists, but **it was never sent to Anthea.** It's an internal sketch only, so never call it "what the client saw".

### The spec: the wireframe
`khanya-nathi-pillar-wireframes.html` is attached to both parent ClickUp tasks. Open it in a browser and use the tabs: Pillar pattern · ECD · ECD Resources · Academic & Career · **Data model (Axel)**. It has every ACF field name, type and return format. **Its field names win.** Summary:
- **New CPT `roots-resource`** (title only, not publicly queryable, no archive) and **taxonomy `roots-area`** (hierarchical, 5 terms; the term slug is the anchor ID).
- **Options page "Help Contacts"**, with a `help_contacts` repeater: `name`, `contact`, `contact_kind` (phone/url), `show_on_ecd`.
- **7 new field groups:**
  - **Pillar: Featured resource** (pages 271 and 515): `show_featured`, `featured_eyebrow`, `featured_title`, `featured_text`, `featured_media`, `featured_image`, `featured_video`, `guide_file`, `cta_1`, `cta_2`, `partner_label`, `partner_logo`
  - **ECD: ROOTS cards** (271): `roots_heading`, `roots_cards` repeater ×5 with `letter`, `title`, `meaning`, `video`, `download_label`, `download_file`, `list_anchor`
  - **ROOTS resource details**: `helps_with`, `resource_kind` (link/pdf/phone/none), `link_url`, `resource_file`, `phone`, `sort_order`
  - **ROOTS area term fields**: `letter`, `meaning`, `area_intro`, `area_order`
  - **ECD Resources page**: `full_list_pdf`
  - **Academic: CareerXplora sections** (515): `benefits` repeater (icon, title, text) and `steps` repeater (title, text)
  - **Help contacts** (options page)
- **Edit the existing CPTs:** add the "Resource media" group (`resource_video`, `video_kind` learner/social, `resource_file`) to `academic-support`, `ecd-support`, `admin-support`, `healthy-mind` and `money-sense`.
- **5 Breakdance Global Blocks** bound to ACF, each **hidden when its fields are empty**: Featured, ROOTS cards, Benefits, Steps, Video gallery (click-to-load, `youtube-nocookie`, supports **Shorts 9:16**).
- **Videos** are `academic-support` posts tagged "Videos", split by `video_kind`, with **no topic category**.
- **Clean-up (not billable):**
  - Delete the legacy CPT `ecd-topic` (+ `ecd-category`, `ecd-tag`), after checking nothing queries it
  - Delete the lorem ipsum posts in all 5 CPTs
  - Delete the stray pages `the-5-pillards` and `sample-page`
  - Fix `/ecd/school-readinesss/` → `school-readiness` with a 301 in Rank Math
  - Remove the homepage "Chat on WhatsApp" button
  - Keep the `story` CPT hidden
  - **Check the ACF PRO licence first**
- **Fix the Academic H1:** staging says "Career & Educational Development"; it should be "Academic and Career Support".

### Corrections to the wireframe (agreed after it was made)
1. **"Helpful resources" library on ECD: hide it, don't delete it.** On 15 Sep I told Anthea it's hidden until there's content.
2. **"Ready to take the next step?" CTA:** it's signed-off boilerplate on every pillar page. Only hide it on ECD if the other 4 pillars on staging don't show it either.
3. **The guide is now v2**, titled **"A Love Letter of Hope – For the caregiver of a little one who has lost a parent."** The wireframe's featured text was written for v1. For now use: eyebrow "FREE GUIDE FOR CAREGIVERS", title "A Love Letter of Hope", text "For the caregiver of a little one who has lost a parent."
4. **Videos:** Fairheads uploads them to **their own YouTube channel (unlisted)** and sends links. **We don't upload anything.** On 27 Oct she sent 4 CareerXplora learner videos, all Shorts:
   - https://youtube.com/shorts/ZHoTCJi7UHY
   - https://youtube.com/shorts/IP5RfFD6MWA
   - https://youtube.com/shorts/xvJOhpl6-ZA
   - https://youtube.com/shorts/ZLzjT5znS8o
   
   Still to come: the 4 social clips, and which video is "What is CareerXplora?".
5. **3 paid or login-only links**: Etsy "Interpersonal effectiveness", the Scribd "Child Development Milestone Chart" and Readers Warehouse "Understanding & Exploring Emotions". Import them as **Draft**; Anthea has never been asked about them.
6. **Phone cells** must give one number per `tel:` link. Police / GBV becomes two buttons, 10111 and 0800 428 428. FAMSA dials 011 975 7106.

### ROOTS content (exact, from the "Start Here" tab of her spreadsheet)
| | Area | Meaning | Anchor | Count |
|---|---|---|---|---|
| R | Relationships | I am loved. I belong. | `relationships` | 9 |
| O | Opportunities to Learn | I can discover and grow. | `opportunities-to-learn` | 11 |
| O | Order / Routine | I know what to expect. | `order-routine` | 2 |
| T | Talk, Tell Stories, Teach | I can express myself and make sense of my world. | `talk-tell-stories-teach` | 11 |
| S | Safety, Hope & Self-Belief | I am safe. Tomorrow can be better. | `safety-hope-self-belief` | 20 |

- That's 53 resources in total.
- 11 of them are Fairheads PDFs not yet supplied: Routine resource, Feelings Wheel, Breathing Techniques, Emotional Regulation, Worry and Anxiety, Interpersonal Effectiveness – Communication, Trauma Response, Trauma Resource, Boundaries, Resilience and Self-Esteem. Show these as **"PDF to come"**.
- There are 14 help contacts. Police, Ambulance and Childline (0800 055 555) have `show_on_ecd` on.
- **Import files:** `roots-resources-import.csv` and `help-contacts-import.csv`, on ClickUp subtask ECD 2. I've checked them against her spreadsheet and they match.

### Still waiting on Anthea: build around these, never block on them
- Final Caregiver Guide (use **v2**)
- PDF of the resource list (hide the button)
- The 11 PDFs ("PDF to come")
- 5 ROOTS caregiver videos (hide the slots)
- An intro sentence per area (hide when empty)
- The 4 social clip links

### Open questions for Anthea (not answered yet)
1. The v2 guide title and wording on the ECD page.
2. The ROOTS area names: her spreadsheet and her printed guide differ ("Opportunity to Learn", "Safety/Self Belief").
3. Phone numbers that differ between her two spreadsheet tabs:
   - FAMSA: 011 975 7106/7 vs 021 447 7951
   - Childline: 116 vs 0800 055 555
   - Child Welfare: 0800 323 1310 vs 021 638 3127
4. The guide still has a "QR code here" placeholder. She needs the final URL `khanyanathi.co.za/ecd/resources/` before it goes to print.
5. The 3 paid or login-only links.

## ClickUp (list "Khanya Nathi - Website Build", id 901523329276)
- **ECD parent:** https://app.clickup.com/t/12487v2dtcu
  - ECD 1 Pre-flight + ACF data structure: `12487v2hrht`
  - ECD 2 Import: `12487v2hrhu`
  - ECD 3 Global Blocks: `12487v2hrhw`
  - ECD 4 ECD page: `12487v2hrhx`
  - ECD 5 Resource list page: `12487v2hrhz`
  - ECD 6 Clean-up: `12487v2hrj0`
  - ECD 7 QA + hand-off: `12487v2hrj2`
- **Academic & Career parent:** https://app.clickup.com/t/12487v2dtct
  - A&C 1 CareerXplora panel: `12487v2hrj3`
  - A&C 2 Benefits + Steps copy: `12487v2hrj6`
  - A&C 3 Video gallery: `12487v2hrj8`
  - A&C 4 Helpful info + QA: `12487v2hrja`
- Each subtask has its files attached. If you have ClickUp tools, read the subtask before you build it.

## Key documents
- Build Guide (signed-off copy + boilerplate): https://docs.google.com/document/d/13FbQv-s51udNzOOf0z72wO01DLP-KoZTKBEAmnOxfYQ/edit
- Page Tracker: https://docs.google.com/spreadsheets/d/1Xwu5itDrZ6bR-d5F1t9Suk8m4msaeHfR-zV3ynWosdA/edit
- Anthea's August feedback: https://drive.google.com/file/d/1wAJyaUJDnTM2P-4FjdqPQK67KhTQYD0W/view
- 29 Sep meeting notes: https://docs.google.com/document/d/1mWJhPRFpZ9q55NCD4rJrrts9aDdnc4EOk6nx65CMEBw/edit
- Resource list spreadsheet (Drive): https://drive.google.com/file/d/187DAQv3XHdg9eICkR8J93FFkcjEhw-BM/view

## Other open items (not this job)
- DNS pointing to the dedicated server, with Johan
- Remove the remaining dummy content before launch
- The original September launch has slipped, and no new go-live date is set
- **Security:** ClickUp task "Set up development site" (`86c9t4k82`) has the KonsoleH login in plain text. Change that password and move it to a password manager. **Never write credentials into docs, commits or tasks.**

## How to work with me
1. Start with **ECD 1**: confirm ACF PRO is active, then create the data model on the LocalWP site using the wireframe's field names. Confirm each step with me before moving on.
2. Use WP-CLI through LocalWP's **Open site shell**. Commit `wp-content/acf-json` and `mu-plugins` changes to the branch above.
3. Don't contact Anthea or change ClickUp tasks without asking me first.
