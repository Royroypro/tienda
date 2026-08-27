(function () {
  "use strict";

  const meta = document.querySelector('meta[name="csrf-token"]');
  if (!meta) return;
  const token = meta.getAttribute("content");

  const originalOpen = XMLHttpRequest.prototype.open;
  const originalSend = XMLHttpRequest.prototype.send;
  XMLHttpRequest.prototype.open = function (method, url) {
    this.__sehuachoUrl = url;
    return originalOpen.apply(this, arguments);
  };
  XMLHttpRequest.prototype.send = function () {
    if (this.__sehuachoUrl && !/^https?:\/\//i.test(this.__sehuachoUrl)) {
      this.setRequestHeader("X-CSRF-Token", token);
    } else if (this.__sehuachoUrl && this.__sehuachoUrl.indexOf(window.location.origin) === 0) {
      this.setRequestHeader("X-CSRF-Token", token);
    }
    return originalSend.apply(this, arguments);
  };

  const originalFetch = window.fetch;
  if (originalFetch) {
    window.fetch = function (resource, options) {
      const url = typeof resource === "string" ? resource : resource.url;
      if (!/^https?:\/\//i.test(url) || url.indexOf(window.location.origin) === 0) {
        options = options || {};
        options.headers = new Headers(options.headers || {});
        options.headers.set("X-CSRF-Token", token);
      }
      return originalFetch.call(this, resource, options);
    };
  }

  if (window.jQuery) {
    window.jQuery.ajaxSetup({ headers: { "X-CSRF-Token": token } });
  }
})();
