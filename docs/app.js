// Initialize Mermaid Diagrams
mermaid.initialize({
  theme: "base",
  themeVariables: {
    primaryColor: "#fef2f2",
    primaryTextColor: "#0f172a",
    primaryBorderColor: "#fca5a5",
    lineColor: "#94a3b8",
    secondaryColor: "#f8fafc",
    tertiaryColor: "#ffffff"
  }
});

// Setup Code Copy Buttons for generic pre blocks
document.querySelectorAll("pre:not(.response-body-pre)").forEach((block) => {
  const button = document.createElement("button");
  button.className = "copy-button";
  button.type = "button";
  button.textContent = "Copy";
  button.addEventListener("click", async () => {
    const code = block.querySelector("code")?.innerText ?? "";
    await navigator.clipboard.writeText(code);
    button.textContent = "Copied";
    window.setTimeout(() => {
      button.textContent = "Copy";
    }, 1400);
  });
  block.appendChild(button);
});

// Generate Table of Contents (On This Page)
const sections = [...document.querySelectorAll(".doc-article section[id]")];
const toc = document.querySelector("#toc");

if (toc && sections.length) {
  sections.forEach((section) => {
    const title = section.dataset.title || section.querySelector("h2, h1")?.textContent || section.id;
    const link = document.createElement("a");
    link.href = `#${section.id}`;
    link.textContent = title;
    toc.appendChild(link);
  });
}

// Sidebar & TOC Active Scrolling Highlight
const navLinks = [...document.querySelectorAll(".docs-sidebar a, .on-this-page a")];

if (sections.length && navLinks.length) {
  const observer = new IntersectionObserver((entries) => {
    const active = entries
      .filter((entry) => entry.isIntersecting)
      .sort((a, b) => b.intersectionRatio - a.intersectionRatio)[0];

    if (!active) return;

    navLinks.forEach((link) => {
      link.classList.toggle("active", link.getAttribute("href") === `#${active.target.id}`);
    });
  }, {
    rootMargin: "-18% 0px -68% 0px",
    threshold: [0.05, 0.2, 0.4]
  });

  sections.forEach((section) => observer.observe(section));
}

// Search Filter Sidebar Group Links
const search = document.querySelector("#docSearch");

if (search) {
  search.addEventListener("input", () => {
    const query = search.value.trim().toLowerCase();
    document.querySelectorAll(".docs-sidebar a").forEach((link) => {
      // Don't filter out sidebar mobile headers
      if (link.classList.contains("close-sidebar-btn")) return;
      link.style.display = !query || link.textContent.toLowerCase().includes(query) ? "" : "none";
    });
  });
}

// Theme Engine (Light / Dark / System Sync)
const themeButtons = document.querySelectorAll(".theme-control-btn");
let currentTheme = localStorage.getItem("docs_theme") || "system";

function applyTheme(theme) {
  const root = document.documentElement;
  
  if (theme === "dark") {
    root.setAttribute("data-theme", "dark");
  } else if (theme === "light") {
    root.removeAttribute("data-theme");
  } else {
    // System Theme resolution
    const prefersDark = window.matchMedia("(prefers-color-scheme: dark)").matches;
    if (prefersDark) {
      root.setAttribute("data-theme", "dark");
    } else {
      root.removeAttribute("data-theme");
    }
  }
  
  // Update Segmented Button classes
  themeButtons.forEach((btn) => {
    btn.classList.toggle("active", btn.dataset.themeVal === theme);
  });
}

// Initial Theme execution
applyTheme(currentTheme);

themeButtons.forEach((btn) => {
  btn.addEventListener("click", () => {
    currentTheme = btn.dataset.themeVal;
    localStorage.setItem("docs_theme", currentTheme);
    applyTheme(currentTheme);
  });
});

// Listen to System prefers-color-scheme changes
window.matchMedia("(prefers-color-scheme: dark)").addEventListener("change", () => {
  if (currentTheme === "system") {
    applyTheme("system");
  }
});

// Mobile Sidebar Drawer Menu controls
const mobileMenuBtn = document.querySelector("#mobileMenuBtn");
const closeSidebarBtn = document.querySelector("#closeSidebarBtn");
const docsSidebar = document.querySelector("#docsSidebar");
const sidebarBackdrop = document.querySelector("#sidebarBackdrop");

function openSidebarDrawer() {
  if (docsSidebar && sidebarBackdrop) {
    docsSidebar.classList.add("open");
    sidebarBackdrop.classList.add("active");
    document.body.style.overflow = "hidden"; // Prevent scrolling behind overlay
  }
}

function closeSidebarDrawer() {
  if (docsSidebar && sidebarBackdrop) {
    docsSidebar.classList.remove("open");
    sidebarBackdrop.classList.remove("active");
    document.body.style.overflow = "";
  }
}

if (mobileMenuBtn) {
  mobileMenuBtn.addEventListener("click", openSidebarDrawer);
}
if (closeSidebarBtn) {
  closeSidebarBtn.addEventListener("click", closeSidebarDrawer);
}
if (sidebarBackdrop) {
  sidebarBackdrop.addEventListener("click", closeSidebarDrawer);
}

// Auto-close drawer when navigating via link on mobile
document.querySelectorAll(".docs-sidebar a").forEach((link) => {
  link.addEventListener("click", () => {
    if (window.innerWidth <= 768) {
      closeSidebarDrawer();
    }
  });
});

// Token Configuration persistence
const tokenInput = document.querySelector("#apiTokenInput");
const saveBtn = document.querySelector("#saveTokenBtn");
let apiToken = localStorage.getItem("hcloud_token") || "";

if (tokenInput && apiToken) {
  tokenInput.value = apiToken;
}

if (saveBtn) {
  saveBtn.addEventListener("click", () => {
    apiToken = tokenInput.value.trim();
    localStorage.setItem("hcloud_token", apiToken);
    alert("API Token saved locally!");
  });
}

// Dynamic API Tester Execution Handler
document.querySelectorAll(".tester-box").forEach((tester) => {
  const method = tester.dataset.method;
  const rawPath = tester.dataset.path;
  const runBtn = tester.querySelector(".run-btn");
  const responsePanel = tester.querySelector(".tester-response");
  const statusDot = tester.querySelector(".status-dot");
  const statusLabel = tester.querySelector(".status-label");
  const latencyLabel = tester.querySelector(".response-latency");
  
  const limitVal = tester.querySelector(".limit-val");
  const remainingVal = tester.querySelector(".remaining-val");
  const resetVal = tester.querySelector(".reset-val");
  
  const codeOutput = tester.querySelector(".response-code");
  const copyResponseBtn = tester.querySelector(".copy-response-btn");

  if (!runBtn) return;

  runBtn.addEventListener("click", async () => {
    if (!apiToken) {
      alert("Please enter and save your Hetzner Cloud API Token in the sidebar authentication widget first!");
      if (tokenInput) tokenInput.focus();
      return;
    }

    // Build URL Path
    let path = rawPath;
    let queryParams = [];
    let missingRequired = false;

    // Fetch parameters inputs
    tester.querySelectorAll(".param-input").forEach((input) => {
      const name = input.dataset.paramName;
      const val = input.value.trim();
      const required = input.required;

      if (required && !val) {
        missingRequired = true;
        input.focus();
      }

      if (val) {
        if (path.includes(`{${name}}`)) {
          path = path.replace(`{${name}}`, encodeURIComponent(val));
        } else {
          queryParams.push(`${name}=${encodeURIComponent(val)}`);
        }
      }
    });

    if (missingRequired) {
      alert("Please fill out all required parameters.");
      return;
    }

    const queryString = queryParams.length > 0 ? `?${queryParams.join("&")}` : "";
    const url = `https://api.hetzner.cloud/v1${path}${queryString}`;

    // Build Request Options
    let fetchOptions = {
      method: method,
      headers: {
        "Authorization": `Bearer ${apiToken}`,
        "Accept": "application/json",
        "Content-Type": "application/json"
      }
    };

    const bodyInput = tester.querySelector(".body-input");
    if (bodyInput) {
      try {
        JSON.parse(bodyInput.value); // Verify valid JSON
        fetchOptions.body = bodyInput.value;
      } catch (err) {
        alert("Invalid JSON in Request Body!");
        bodyInput.focus();
        return;
      }
    }

    // Run Request
    responsePanel.style.display = "block";
    statusDot.className = "status-dot";
    statusLabel.textContent = "Sending request...";
    latencyLabel.textContent = "";

    const start = Date.now();

    try {
      const res = await fetch(url, fetchOptions);
      const latency = Date.now() - start;
      latencyLabel.textContent = `${latency}ms`;

      // Update rate limits
      updateRateLimits(res.headers);

      // Status Badge
      statusDot.className = "status-dot";
      const statusText = `${res.status} ${res.statusText || getStatusText(res.status)}`;
      statusLabel.textContent = statusText;

      if (res.status >= 200 && res.status < 300) {
        statusDot.classList.add("success");
      } else if (res.status >= 400 && res.status < 500) {
        statusDot.classList.add("warning");
      } else {
        statusDot.classList.add("error");
      }

      // Output Response Body
      const text = await res.text();
      try {
        const parsed = JSON.parse(text);
        codeOutput.textContent = JSON.stringify(parsed, null, 2);
      } catch (e) {
        codeOutput.textContent = text;
      }

    } catch (err) {
      statusDot.className = "status-dot error";
      statusLabel.textContent = "Connection Failure";
      codeOutput.textContent = `Error: ${err.toString()}\n\nNote: Outbound connections to api.hetzner.cloud must be allowed. Check developer console for CORS block warnings.`;
    }
  });

  if (copyResponseBtn && codeOutput) {
    copyResponseBtn.addEventListener("click", async () => {
      const text = codeOutput.textContent || "";
      if (!text || text === "Sending request...") return;
      await navigator.clipboard.writeText(text);
      
      const originalText = copyResponseBtn.textContent;
      copyResponseBtn.textContent = "Copied!";
      copyResponseBtn.style.background = "var(--green)";
      copyResponseBtn.style.borderColor = "var(--green)";
      copyResponseBtn.style.color = "#ffffff";
      
      window.setTimeout(() => {
        copyResponseBtn.textContent = originalText;
        copyResponseBtn.style.background = "";
        copyResponseBtn.style.borderColor = "";
        copyResponseBtn.style.color = "";
      }, 1500);
    });
  }

  function updateRateLimits(headers) {
    const limit = headers.get("RateLimit-Limit") || headers.get("x-ratelimit-limit") || "-";
    const remaining = headers.get("RateLimit-Remaining") || headers.get("x-ratelimit-remaining") || "-";
    const reset = headers.get("RateLimit-Reset") || headers.get("x-ratelimit-reset") || "-";

    if (limitVal) limitVal.textContent = limit;
    if (remainingVal) remainingVal.textContent = remaining;

    if (resetVal) {
      if (reset !== "-") {
        const diff = Math.max(0, parseInt(reset) - Math.floor(Date.now() / 1000));
        resetVal.textContent = `${diff}s`;
        
        // Update global top limits if present
        const globalReset = document.querySelector("#globalResetVal");
        if (globalReset) globalReset.textContent = `${diff}s`;
      } else {
        resetVal.textContent = "-";
      }
    }

    // Sync global headers
    const globalLimit = document.querySelector("#globalLimitVal");
    const globalRemaining = document.querySelector("#globalRemainingVal");
    if (globalLimit && limit !== "-") globalLimit.textContent = limit;
    if (globalRemaining && remaining !== "-") globalRemaining.textContent = remaining;
  }

  function getStatusText(status) {
    const texts = {
      200: "OK",
      201: "Created",
      204: "No Content",
      400: "Bad Request",
      401: "Unauthorized",
      403: "Forbidden",
      404: "Not Found",
      422: "Unprocessable Entity",
      429: "Too Many Requests",
      500: "Internal Server Error"
    };
    return texts[status] || "";
  }
});
