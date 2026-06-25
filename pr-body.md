# Audit report

This audit fix resolves 2 of the total 25 vulnerabilities found in your project.

## Updated dependencies
* [@nextcloud/cypress](#user-content-\\@nextcloud\\/cypress)
* [vite](#user-content-vite)
## Fixed vulnerabilities

### `@nextcloud/cypress` <a href="#user-content-\@nextcloud\/cypress" id="\@nextcloud\/cypress">#</a>
* Caused by vulnerable dependency:
  * [cypress](#user-content-cypress)
* Affected versions: 
* Package usage:
  * `node_modules/@nextcloud/cypress`

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

qs  6.11.1 - 6.15.1
Severity: moderate
qs has a remotely triggerable DoS: qs.stringify crashes with TypeError on null/undefined entries in comma-format arrays when encodeValuesOnly is set - https://github.com/advisories/GHSA-q8mj-m7cp-5q26
fix available via `npm audit fix`
node_modules/qs
  @cypress/request  <=4.0.0
  Depends on vulnerable versions of qs
  Depends on vulnerable versions of uuid
  node_modules/@cypress/request
    cypress  4.3.0 - 15.14.2
    Depends on vulnerable versions of @cypress/request
    node_modules/cypress
      @nextcloud/cypress  
      Depends on vulnerable versions of cypress
      node_modules/@nextcloud/cypress

uuid  <11.1.1
Severity: moderate
uuid: Missing buffer bounds check in v3/v5/v6 when buf is provided - https://github.com/advisories/GHSA-w5hq-g745-h8pq
fix available via `npm audit fix`
node_modules/@nextcloud/cypress/node_modules/uuid
node_modules/uuid
  dockerode  4.0.3 - 4.0.12
  Depends on vulnerable versions of uuid
  node_modules/@nextcloud/cypress/node_modules/dockerode

13 vulnerabilities (7 low, 6 moderate)

To address issues that do not require attention, run:
  npm audit fix

Some issues need review, and may require choosing
a different dependency.
```

**Node.js:** v24.17.0 | **npm:** 11.17.0 | **Branch:** stable34
