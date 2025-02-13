# BREAKING CHANGE NOTE

- Enum ImageResolutionDPI updated to string, related to dynamic
node creation. See after all changes to revert it.
- MarkdownPdfBuilder `wrapper` and `wrapperFile` method removed.
At the origin it was a copy/paste of `content` and `contentFile`, 
it's served now by Chromium/ContentTrait where you can find these methods.

