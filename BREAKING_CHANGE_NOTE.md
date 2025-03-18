# BREAKING CHANGE NOTE
- due to beforeNormalization issue uncomment WebhookNodeBuilder +
revert testBuilderWebhookConfiguredWithValidConfiguration with string config name
https://github.com/symfony/symfony/issues/59877
- due to beforeNormalization issue UnitNodeBuilder, we normally accept 
string, int or float and parse the value to be valid with arg value and unit
for marginTop,....

