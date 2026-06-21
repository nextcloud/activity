# Audit report

This audit fix resolves 1 of the total 16 vulnerabilities found in your project.

## Updated dependencies
* [vite](#user-content-vite)
## Fixed vulnerabilities

### `vite` <a href="#user-content-vite" id="vite">#</a>
* launch-editor: NTLMv2 hash disclosure via UNC path handling on Windows
* Severity: **moderate**
* Reference: [https://github.com/advisories/GHSA-v6wh-96g9-6wx3](https://github.com/advisories/GHSA-v6wh-96g9-6wx3)
* Affected versions: 7.0.0 - 7.3.3
* Package usage:
  * `node_modules/vite`


## Full `npm audit` report

```
# npm audit report

elliptic  *
Elliptic Uses a Cryptographic Primitive with a Risky Implementation - https://github.com/advisories/GHSA-848j-6mx2-7j84
No fix available
node_modules/elliptic
  browserify-sign  >=2.4.0
  Depends on vulnerable versions of elliptic
  node_modules/browserify-sign
    crypto-browserify  >=3.4.0
    Depends on vulnerable versions of browserify-sign
    Depends on vulnerable versions of create-ecdh
    node_modules/crypto-browserify
      node-stdlib-browser  *
      Depends on vulnerable versions of crypto-browserify
      node_modules/node-stdlib-browser
        vite-plugin-node-polyfills  >=0.3.0
        Depends on vulnerable versions of node-stdlib-browser
        node_modules/vite-plugin-node-polyfills
          @nextcloud/vite-config  *
          Depends on vulnerable versions of vite-plugin-node-polyfills
          node_modules/@nextcloud/vite-config
  create-ecdh  *
  Depends on vulnerable versions of elliptic
  node_modules/create-ecdh

uuid  <11.1.1
Severity: moderate
uuid: Missing buffer bounds check in v3/v5/v6 when buf is provided - https://github.com/advisories/GHSA-w5hq-g745-h8pq
fix available via `npm audit fix`
node_modules/uuid
  dockerode  4.0.3 - 4.0.12
  Depends on vulnerable versions of uuid
  node_modules/@nextcloud/e2e-test-server/node_modules/dockerode

9 vulnerabilities (7 low, 2 moderate)

To address issues that do not require attention, run:
  npm audit fix

Some issues need review, and may require choosing
a different dependency.
```

**Node.js:** v24.16.0 | **npm:** 11.17.0 | **Branch:** master
