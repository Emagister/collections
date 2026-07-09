# Changelog

## [1.5.1](https://github.com/Emagister/collections/compare/v1.5.0...v1.5.1) (2026-07-08)


### Bug Fixes

* use value equality for objects in contains()/remove(), keep strict scalar comparison ([e4ad273](https://github.com/Emagister/collections/commit/e4ad2735e977207c3e1d4780444935f4e71c6f7b))


### Code Refactoring

* remove redundant comment on elementsAreEqual() ([c6230e1](https://github.com/Emagister/collections/commit/c6230e11a9cdcb4902a04e20a737e980393882d5))


### Tests

* extend remove() coverage for object value-equality and absent elements ([a0eb124](https://github.com/Emagister/collections/commit/a0eb1245e73d026520fdac92792b8c7edceba168))
* rename remove() type-safety test to reflect strict-match behavior ([38ff022](https://github.com/Emagister/collections/commit/38ff022d95fe5afea1668980ba767aa39c9be41a))


## [1.5.0](https://github.com/Emagister/collections/compare/v1.4.0...v1.5.0) (2026-07-08)


### Features

* **ci:** add PR validation workflow with lint, tests, CodeSniffer and CS Fixer ([f6336ac](https://github.com/Emagister/collections/commit/f6336accf702949e1cad2425de8cca1e70aaabd9)), closes [#11](https://github.com/Emagister/collections/issues/11)


### Bug Fixes

* **ci:** add phpcs.xml.dist and pass --allow-risky to CS Fixer ([b06d9f8](https://github.com/Emagister/collections/commit/b06d9f8951c984b8bedb30e14cff7f3e7265f2ae))
* **ci:** apply CS Fixer formatting and pin platform to PHP 8.1 ([88bbd42](https://github.com/Emagister/collections/commit/88bbd423b6e1fbab6871b675c9776dbe2dc87526))
* **cs:** remove blank lines before closing brace in find/findNot ([bc2fa46](https://github.com/Emagister/collections/commit/bc2fa464df333cce056b428f04e882b8c15a1ab7))
* **cs-fixer:** fail fast on missing or invalid rules JSON ([eef87e0](https://github.com/Emagister/collections/commit/eef87e09964838b889c0030399cb882a852ea152)), closes [#25](https://github.com/Emagister/collections/issues/25)
* **Makefile:** lint each PHP file separately ([19ac8f0](https://github.com/Emagister/collections/commit/19ac8f088a75ac6ca4ea894419159645d755de58)), closes [#24](https://github.com/Emagister/collections/issues/24)
* **Makefile:** use CURDIR and preserve host user in Docker ([c5f4b05](https://github.com/Emagister/collections/commit/c5f4b05f69e05362711d1ad52750782c1776b43c)), closes [#26](https://github.com/Emagister/collections/issues/26)


### Code Refactoring

* **ci:** let phpcs read paths from phpcs.xml.dist ([4c5f3a9](https://github.com/Emagister/collections/commit/4c5f3a98d80d092b93e249cc587a6a54b024a629))
* **ci:** rename composer test script to tests ([b35c7a2](https://github.com/Emagister/collections/commit/b35c7a25964ccf4c26c7f5523bca459e5ba87df6))
* **ci:** use composer scripts as single source of truth ([b043ef1](https://github.com/Emagister/collections/commit/b043ef1a4aac7311cef1e22b179dbac60a99309e))

## [1.4.0](https://github.com/Emagister/collections/compare/v1.3.2...v1.4.0) (2026-07-08)


### Features

* **release:** modernize workflow with Conventional Commits versioning and CHANGELOG generation ([b3e3c18](https://github.com/Emagister/collections/commit/b3e3c18f8cce340110bd0898b12da49bf802e184)), closes [#10](https://github.com/Emagister/collections/issues/10) [#12](https://github.com/Emagister/collections/issues/12)


### Bug Fixes

* **release:** add concurrency group to serialize releases ([9acc018](https://github.com/Emagister/collections/commit/9acc018937dc7479c106b2c53e46d2a451ea218d)), closes [#19](https://github.com/Emagister/collections/issues/19)
* **release:** checkout main explicitly to avoid detached HEAD ([d636ba3](https://github.com/Emagister/collections/commit/d636ba36761a6ac4a95dd128e64901f0fe03bf50)), closes [#20](https://github.com/Emagister/collections/issues/20)
* **release:** guard pull_request fields behind event_name check ([0441fba](https://github.com/Emagister/collections/commit/0441fba58c43343b4fe91168a5350c3a13bc80ed)), closes [#22](https://github.com/Emagister/collections/issues/22)
* **release:** push HEAD:main instead of branch name ([fd9e42b](https://github.com/Emagister/collections/commit/fd9e42b74f5773c15e2607c345db287313b7f7d4)), closes [#21](https://github.com/Emagister/collections/issues/21)
* **release:** update composer.lock after version bump ([e9c5f62](https://github.com/Emagister/collections/commit/e9c5f621f931148aa0f1dfa3a850fbfafd1fdb49))


### Continuous Integration

* **release:** shorten workflow display name ([4233303](https://github.com/Emagister/collections/commit/4233303ecc795ff5370fe4f38f1ade5a966f5192))


