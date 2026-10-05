<?php

namespace Sensiolabs\GotenbergBundle\Builder\Behaviors;

use Sensiolabs\GotenbergBundle\Builder\Attributes\NormalizeGotenbergPayload;
use Sensiolabs\GotenbergBundle\Builder\Attributes\WithConfigurationNode;
use Sensiolabs\GotenbergBundle\Builder\BodyBag;
use Sensiolabs\GotenbergBundle\Builder\Util\NormalizerFactory;
use Sensiolabs\GotenbergBundle\Exception\InvalidBuilderConfiguration;
use Sensiolabs\GotenbergBundle\NodeBuilder\BooleanNodeBuilder;
use Sensiolabs\GotenbergBundle\NodeBuilder\ScalarNodeBuilder;

/**
 * @package Behavior\\Encrypt
 *
 * @see https://gotenberg.dev/docs/manipulate-pdfs/encrypt-pdfs
 */
trait EncryptTrait
{
    abstract protected function getBodyBag(): BodyBag;

    /**
     * Set PDF user password.
     *
     * @example userPassword('UserDefinedPassword')
     */
    #[WithConfigurationNode(new ScalarNodeBuilder('user_password', restrictTo: 'string'))]
    public function userPassword(#[\SensitiveParameter] string|null $userPassword): self
    {
        $this->logWarningIfVersionIs('<', '8.25', 'User password option is not available.');

        if (null === $userPassword) {
            $this->getBodyBag()->unset('userPassword');
        } else {
            $this->getBodyBag()->set('userPassword', $userPassword);
        }

        return $this;
    }

    /**
     * Set PDF owner password.
     *
     * @example ownerPassword('OwnerDefinedPassword')
     */
    #[WithConfigurationNode(new ScalarNodeBuilder('owner_password', restrictTo: 'string'))]
    public function ownerPassword(#[\SensitiveParameter] string|null $ownerPassword): self
    {
        $this->logWarningIfVersionIs('<', '8.25', 'Owner password option is not available.');

        if (null === $ownerPassword) {
            $this->getBodyBag()->unset('ownerPassword');
        } else {
            $this->getBodyBag()->set('ownerPassword', $ownerPassword);
        }

        return $this;
    }

    /**
     * Allow printing the document (default true).
     * Restricting it requires a user or an owner password.
     *
     * @see https://gotenberg.dev/docs/manipulate-pdfs/encrypt-pdfs
     *
     * @example allowPrinting(false)
     */
    #[WithConfigurationNode(new BooleanNodeBuilder('allow_printing'))]
    public function allowPrinting(bool $bool = true): static
    {
        $this->logWarningIfVersionIs('<', '8.34', 'The option allowPrinting is not available.');

        $this->getBodyBag()->set('allowPrinting', $bool);

        return $this;
    }

    /**
     * Allow extracting text and graphics from the document (default true).
     * Restricting it requires a user or an owner password.
     *
     * @see https://gotenberg.dev/docs/manipulate-pdfs/encrypt-pdfs
     *
     * @example allowCopying(false)
     */
    #[WithConfigurationNode(new BooleanNodeBuilder('allow_copying'))]
    public function allowCopying(bool $bool = true): static
    {
        $this->logWarningIfVersionIs('<', '8.34', 'The option allowCopying is not available.');

        $this->getBodyBag()->set('allowCopying', $bool);

        return $this;
    }

    /**
     * Allow changing the document content (default true).
     * Restricting it requires a user or an owner password.
     *
     * @see https://gotenberg.dev/docs/manipulate-pdfs/encrypt-pdfs
     *
     * @example allowModifying(false)
     */
    #[WithConfigurationNode(new BooleanNodeBuilder('allow_modifying'))]
    public function allowModifying(bool $bool = true): static
    {
        $this->logWarningIfVersionIs('<', '8.34', 'The option allowModifying is not available.');

        $this->getBodyBag()->set('allowModifying', $bool);

        return $this;
    }

    /**
     * Allow adding or modifying annotations (default true).
     * Restricting it requires a user or an owner password.
     *
     * @see https://gotenberg.dev/docs/manipulate-pdfs/encrypt-pdfs
     *
     * @example allowAnnotating(false)
     */
    #[WithConfigurationNode(new BooleanNodeBuilder('allow_annotating'))]
    public function allowAnnotating(bool $bool = true): static
    {
        $this->logWarningIfVersionIs('<', '8.34', 'The option allowAnnotating is not available.');

        $this->getBodyBag()->set('allowAnnotating', $bool);

        return $this;
    }

    /**
     * Allow filling in form fields (default true).
     * Restricting it requires a user or an owner password.
     *
     * @see https://gotenberg.dev/docs/manipulate-pdfs/encrypt-pdfs
     *
     * @example allowFillingForms(false)
     */
    #[WithConfigurationNode(new BooleanNodeBuilder('allow_filling_forms'))]
    public function allowFillingForms(bool $bool = true): static
    {
        $this->logWarningIfVersionIs('<', '8.34', 'The option allowFillingForms is not available.');

        $this->getBodyBag()->set('allowFillingForms', $bool);

        return $this;
    }

    /**
     * Allow inserting, deleting and rotating pages (default true).
     * Restricting it requires a user or an owner password.
     *
     * @see https://gotenberg.dev/docs/manipulate-pdfs/encrypt-pdfs
     *
     * @example allowAssembling(false)
     */
    #[WithConfigurationNode(new BooleanNodeBuilder('allow_assembling'))]
    public function allowAssembling(bool $bool = true): static
    {
        $this->logWarningIfVersionIs('<', '8.34', 'The option allowAssembling is not available.');

        $this->getBodyBag()->set('allowAssembling', $bool);

        return $this;
    }

    /**
     * Gotenberg treats an empty password as a missing one.
     *
     * @param 'userPassword'|'ownerPassword' $field
     */
    private function hasEncryptPassword(string $field): bool
    {
        return '' !== ($this->getBodyBag()->get($field) ?? '');
    }

    private function validateEncryptPermissions(): void
    {
        if ($this->hasEncryptPassword('userPassword') || $this->hasEncryptPassword('ownerPassword')) {
            return;
        }

        foreach (['allowPrinting', 'allowCopying', 'allowModifying', 'allowAnnotating', 'allowFillingForms', 'allowAssembling'] as $permission) {
            if ($this->getBodyBag()->get($permission) === false) {
                throw new InvalidBuilderConfiguration(\sprintf('"%s" can only be restricted when "userPassword" or "ownerPassword" is set.', $permission));
            }
        }
    }

    #[NormalizeGotenbergPayload]
    private function normalizeEncrypt(): \Generator
    {
        yield 'allowPrinting' => NormalizerFactory::bool();
        yield 'allowCopying' => NormalizerFactory::bool();
        yield 'allowModifying' => NormalizerFactory::bool();
        yield 'allowAnnotating' => NormalizerFactory::bool();
        yield 'allowFillingForms' => NormalizerFactory::bool();
        yield 'allowAssembling' => NormalizerFactory::bool();
    }
}
