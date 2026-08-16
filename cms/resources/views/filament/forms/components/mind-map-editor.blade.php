<div class="of-mind-map-editor" data-mind-map-editor>
    <div class="of-mind-map-editor__canvas-shell" data-mind-map-shell>
        <div class="of-mind-map-editor__topbar">
            <button type="button" class="of-mind-map-editor__top-button" data-mind-map-action="exit-fullscreen">
                Back
            </button>

            <div class="of-mind-map-editor__brand">Mind Elixir</div>

            <button type="button" class="of-mind-map-editor__top-button" data-mind-map-action="toggle-inspector">
                Panel
            </button>

            <button type="button" class="of-mind-map-editor__top-button" data-mind-map-action="toggle-theme">
                Theme
            </button>

            <button type="button" class="of-mind-map-editor__top-button of-mind-map-editor__top-button--accent" data-mind-map-action="submit-form">
                Save
            </button>
        </div>

        <div class="of-mind-map-editor__leftbar">
            <button type="button" class="of-mind-map-editor__icon-button" data-mind-map-action="toggle-fullscreen" title="Fullscreen">
                ⛶
            </button>
            <button type="button" class="of-mind-map-editor__icon-button" data-mind-map-action="scale-fit" title="Fit">
                ⌖
            </button>
            <button type="button" class="of-mind-map-editor__icon-button" data-mind-map-action="zoom-in" title="Zoom in">
                +
            </button>
            <button type="button" class="of-mind-map-editor__icon-button" data-mind-map-action="zoom-out" title="Zoom out">
                -
            </button>
            <button type="button" class="of-mind-map-editor__icon-button" data-mind-map-action="center" title="Center">
                ◎
            </button>
            <button type="button" class="of-mind-map-editor__icon-button" data-mind-map-action="layout-side" title="Both sides">
                ⇆
            </button>
            <button type="button" class="of-mind-map-editor__icon-button" data-mind-map-action="layout-left" title="Left">
                ⇤
            </button>
            <button type="button" class="of-mind-map-editor__icon-button" data-mind-map-action="layout-right" title="Right">
                ⇥
            </button>
            <button type="button" class="of-mind-map-editor__icon-button" data-mind-map-action="toggle-compact" title="Compact">
                ☷
            </button>
            <button type="button" class="of-mind-map-editor__icon-button" data-mind-map-action="toggle-branch" title="Expand or collapse branch">
                ⇵
            </button>
        </div>

        <div class="of-mind-map-editor__canvas" data-mind-map-canvas></div>

        <aside class="of-mind-map-editor__inspector">
            <button
                type="button"
                class="of-mind-map-editor__inspector-toggle"
                data-mind-map-action="toggle-inspector"
                title="Collapse panel"
            >
                ❯
            </button>

            <div class="of-mind-map-editor__tabs">
                <button type="button" class="of-mind-map-editor__tab is-active" data-mind-map-tab="general">
                    General
                </button>
                <button type="button" class="of-mind-map-editor__tab" data-mind-map-tab="image">
                    Image
                </button>
                <button type="button" class="of-mind-map-editor__tab" data-mind-map-tab="note">
                    Note
                </button>
            </div>

            <div class="of-mind-map-editor__panel is-active" data-mind-map-panel="general">
                <label class="of-mind-map-editor__field">
                    <span>Topic</span>
                    <input type="text" class="of-mind-map-editor__input" data-inspector-field="topic" placeholder="Node title" />
                </label>

                <div class="of-mind-map-editor__grid of-mind-map-editor__grid--two">
                    <label class="of-mind-map-editor__field">
                        <span>Color</span>
                        <input type="color" class="of-mind-map-editor__color" data-inspector-field="color" />
                    </label>

                    <label class="of-mind-map-editor__field">
                        <span>Background</span>
                        <input type="color" class="of-mind-map-editor__color" data-inspector-field="background" />
                    </label>
                </div>

                <label class="of-mind-map-editor__field">
                    <span>Font size</span>
                    <input type="range" min="12" max="48" step="1" data-inspector-field="font-size" />
                </label>

                <label class="of-mind-map-editor__field">
                    <span>Tags</span>
                    <input type="text" class="of-mind-map-editor__input" data-inspector-field="tags" placeholder="tag1, tag2" />
                </label>

                <div class="of-mind-map-editor__field">
                    <span>Icons</span>
                    <div class="of-mind-map-editor__icon-grid" data-mind-map-icons>
                        <button type="button" class="of-mind-map-editor__emoji" data-icon="⭐">⭐</button>
                        <button type="button" class="of-mind-map-editor__emoji" data-icon="✅">✅</button>
                        <button type="button" class="of-mind-map-editor__emoji" data-icon="❌">❌</button>
                        <button type="button" class="of-mind-map-editor__emoji" data-icon="⚠️">⚠️</button>
                        <button type="button" class="of-mind-map-editor__emoji" data-icon="📌">📌</button>
                        <button type="button" class="of-mind-map-editor__emoji" data-icon="💡">💡</button>
                        <button type="button" class="of-mind-map-editor__emoji" data-icon="🚀">🚀</button>
                        <button type="button" class="of-mind-map-editor__emoji" data-icon="🔥">🔥</button>
                        <button type="button" class="of-mind-map-editor__emoji" data-icon="🎯">🎯</button>
                        <button type="button" class="of-mind-map-editor__emoji" data-icon="🧠">🧠</button>
                        <button type="button" class="of-mind-map-editor__emoji" data-icon="🌍">🌍</button>
                        <button type="button" class="of-mind-map-editor__emoji" data-icon="📅">📅</button>
                    </div>
                </div>

                <label class="of-mind-map-editor__field">
                    <span>URL</span>
                    <input type="url" class="of-mind-map-editor__input" data-inspector-field="url" placeholder="https://..." />
                </label>

                <div class="of-mind-map-editor__actions">
                    <button type="button" class="of-mind-map-editor__action" data-mind-map-action="edit-root">Edit</button>
                    <button type="button" class="of-mind-map-editor__action" data-mind-map-action="add-child">Add child</button>
                    <button type="button" class="of-mind-map-editor__action" data-mind-map-action="add-sibling">Add sibling</button>
                    <button type="button" class="of-mind-map-editor__action" data-mind-map-action="add-parent">Add parent</button>
                    <button type="button" class="of-mind-map-editor__action" data-mind-map-action="move-up">Move up</button>
                    <button type="button" class="of-mind-map-editor__action" data-mind-map-action="move-down">Move down</button>
                    <button type="button" class="of-mind-map-editor__action" data-mind-map-action="toggle-branch">Toggle branch</button>
                    <button type="button" class="of-mind-map-editor__action of-mind-map-editor__action--danger" data-mind-map-action="remove-selected">Delete</button>
                </div>
            </div>

            <div class="of-mind-map-editor__panel" data-mind-map-panel="image">
                <label class="of-mind-map-editor__field">
                    <span>Image URL</span>
                    <input type="url" class="of-mind-map-editor__input" data-inspector-field="image-url" placeholder="https://..." />
                </label>

                <div class="of-mind-map-editor__grid of-mind-map-editor__grid--two">
                    <label class="of-mind-map-editor__field">
                        <span>Width</span>
                        <input type="number" class="of-mind-map-editor__input" data-inspector-field="image-width" min="0" />
                    </label>

                    <label class="of-mind-map-editor__field">
                        <span>Height</span>
                        <input type="number" class="of-mind-map-editor__input" data-inspector-field="image-height" min="0" />
                    </label>
                </div>

                <label class="of-mind-map-editor__field">
                    <span>Object fit</span>
                    <select class="of-mind-map-editor__input" data-inspector-field="image-fit">
                        <option value="">Default</option>
                        <option value="fill">Fill</option>
                        <option value="contain">Contain</option>
                        <option value="cover">Cover</option>
                    </select>
                </label>

                <div class="of-mind-map-editor__actions">
                    <button type="button" class="of-mind-map-editor__action" data-mind-map-action="apply-image">
                        Apply image
                    </button>
                    <button type="button" class="of-mind-map-editor__action" data-mind-map-action="clear-image">
                        Clear image
                    </button>
                </div>
            </div>

            <div class="of-mind-map-editor__panel" data-mind-map-panel="note">
                <label class="of-mind-map-editor__field">
                    <span>Note</span>
                    <textarea class="of-mind-map-editor__textarea" data-inspector-field="note" rows="10" placeholder="Node note"></textarea>
                </label>
            </div>

            <div class="of-mind-map-editor__status" data-mind-map-status>
                Root selected
            </div>
        </aside>
    </div>
</div>
