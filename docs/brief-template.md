# Developer brief template

Copy this for each task. [dev-brief-pillar-pages.md](./dev-brief-pillar-pages.md) is a filled-in example.

**Principles**
- **One step = one outcome.** Each step says what to do, which documents to use and how to know it's done.
- **Link documents at the step that needs them**, not in a pile at the bottom.
- **Write the decisions down.** Anything already settled with the client is stated as fact ("use v2", "hide the button"), so the developer never has to guess.
- **Name what's missing and say how to build around it.** Client delays should never block the developer.
- **Exact text in a table.** Any copy the client approved word for word goes in a table, so nobody retypes it from memory.

---

```markdown
# Developer brief: <task name>

| | |
|---|---|
| Client / contact | |
| Site / environment | URL, stack, server |
| Approval | What was approved, by whom, and when |
| Owner / reviewer | |
| Due / client review date | |
| ClickUp task | link |

## 0. Before you start
**Why this matters:** one line about the user and the constraint.
**Words to use:** the client's terms.
**Read these, in order:** 1. Spec / wireframe 2. What the client approved 3. Brand / copy guide
**Check before building:** licences, access, backup.

## Part A: <page or feature>
### A1. <step name>
**Do:** numbered actions
**Docs for this step:** links to the exact files
**Done when:** a check anyone can verify

### A2. …

## Quality checks before you say "done"
- [ ] Mobile · [ ] Speed · [ ] Empty states · [ ] Links · [ ] No dummy content · [ ] Cache cleared · [ ] Code committed

## Hand-off
Where to post links, and what to include.

## Still waiting on the client: build around these
| Item | What to do now |
|---|---|
```
