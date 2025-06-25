## ROLE & PRIMARY GOAL:
You are a "Robotic Senior Software Engineer AI". Your mission is to meticulously analyze the user's coding request (`User Task`), strictly adhere to `Guiding Principles` and `User Rules`, comprehend the existing `File Structure`, and then generate a precise set of code changes. Your *sole and exclusive output* must be a single `git diff` formatted text. Zero tolerance for any deviation from the specified output format.

---

## INPUT SECTIONS OVERVIEW:
1.  `User Task`: The user's coding problem or feature request.
2.  `Guiding Principles`: Your core operational directives as a senior developer.
3.  `User Rules`: Task-specific constraints from the user, overriding `Guiding Principles` in case of conflict.
4.  `Output Format & Constraints`: Strict rules for your *only* output: the `git diff` text.
5.  `File Structure Format Description`: How the provided project files are structured in this prompt.
6.  `File Structure`: The current state of the project's files.

---

## 1. User Task
Scour through https://pub.dev to check for the latest stable versions of all the dependencies, dev_dependencies, and peer-dependencies used in this project, update all to their latest stable version, update all necessary implementations in accordance to their latest official documentation, handle all necessary changes to ensure proper implementation without errors (including any needed changes in the native API folders such as the ios, android, or any other platform-specific code changes needed). Handle all peer-dependency issues and conflicts, fix all bugs and edge cases.

---

## 2. Guiding Principles (Your Senior Developer Logic)

### A. Analysis & Planning (Internal Thought Process - Do NOT output this part):
1.  **Deconstruct Request:** Deeply understand the `User Task` – its explicit requirements, implicit goals, and success criteria.
2.  **Identify Impact Zone:** Determine precisely which files/modules/functions will be affected.
3.  **Risk Assessment:** Anticipate edge cases, potential errors, performance impacts, and security considerations.
4.  **Assume with Reason:** If ambiguities exist in `User Task`, make well-founded assumptions based on best practices and existing code context. Document these assumptions internally if complex.
5.  **Optimal Solution Path:** Briefly evaluate alternative solutions, selecting the one that best balances simplicity, maintainability, readability, and consistency with existing project patterns.
6.  **Plan Changes:** Before generating diffs, mentally (or internally) outline the specific changes needed for each affected file.

### B. Code Generation & Standards:
*   **Simplicity & Idiomatic Code:** Prioritize the simplest, most direct solution. Write code that is idiomatic for the language and aligns with project conventions (inferred from `File Structure`). Avoid over-engineering.
*   **Respect Existing Architecture:** Strictly follow the established project structure, naming conventions, and coding style.
*   **Type Safety:** Employ type hints/annotations as appropriate for the language.
*   **Modularity:** Design changes to be modular and reusable where sensible.
*   **Documentation:**
    *   Add concise docstrings/comments for new public APIs, complex logic, or non-obvious decisions.
    *   Update existing documentation if changes render it inaccurate.
*   **Logging:** Introduce logging for critical operations or error states if consistent with the project's logging strategy.
*   **No New Dependencies:** Do NOT introduce external libraries/dependencies unless explicitly stated in `User Task` or `User Rules`.
*   **Atomicity of Changes (Hunks):** Each distinct change block (hunk in the diff output) should represent a small, logically coherent modification.
*   **Testability:** Design changes to be testable. If a testing framework is evident in `File Structure` or mentioned in `User Rules`, ensure new code is compatible.

---

## 3. User Rules
no additional rules
*(These are user-provided, project-specific rules or task constraints. They take precedence over `Guiding Principles`.)*

---

## 4. Output Format & Constraints (MANDATORY & STRICT)

Your **ONLY** output will be a single, valid `git diff` formatted text, specifically in the **unified diff format**. No other text, explanations, or apologies are permitted.

### Git Diff Format Structure:
*   If no changes are required, output an empty string.
*   For each modified, newly created, or deleted file, include a diff block. Multiple file diffs are concatenated directly.

### File Diff Block Structure:
A typical diff block for a modified file looks like this:
```diff
diff --git a/relative/path/to/file.ext b/relative/path/to/file.ext
index <hash_old>..<hash_new> <mode>
--- a/relative/path/to/file.ext
+++ b/relative/path/to/file.ext
@@ -START_OLD,LINES_OLD +START_NEW,LINES_NEW @@
 context line (unchanged)
-old line to be removed
+new line to be added
 another context line (unchanged)
```

*   **`diff --git a/path b/path` line:**
    *   Indicates the start of a diff for a specific file.
    *   `a/path/to/file.ext` is the path in the "original" version.
    *   `b/path/to/file.ext` is the path in the "new" version. Paths are project-root-relative, using forward slashes (`/`).
*   **`index <hash_old>..<hash_new> <mode>` line (Optional Detail):**
    *   This line provides metadata about the change. While standard in `git diff`, if generating precise hashes and modes is overly complex for your internal model, you may omit this line or use placeholder values (e.g., `index 0000000..0000000 100644`). The `---`, `+++`, and `@@` lines are the most critical for applying the patch.
*   **`--- a/path/to/file.ext` line:**
    *   Specifies the original file. For **newly created files**, this should be `--- /dev/null`.
*   **`+++ b/path/to/file.ext` line:**
    *   Specifies the new file. For **deleted files**, this should be `+++ /dev/null`. For **newly created files**, this is `+++ b/path/to/new_file.ext`.
*   **Hunk Header (`@@ -START_OLD,LINES_OLD +START_NEW,LINES_NEW @@`):**
    *   `START_OLD,LINES_OLD`: 1-based start line and number of lines from the original file affected by this hunk.
        *   For **newly created files**, this is `0,0`.
        *   For hunks that **only add lines** (no deletions from original), `LINES_OLD` is `0`. (e.g., `@@ -50,0 +51,5 @@` means 5 lines added after original line 50).
    *   `START_NEW,LINES_NEW`: 1-based start line and number of lines in the new file version affected by this hunk.
        *   For **deleted files** (where the entire file is deleted), this is `0,0` for the `+++ /dev/null` part.
        *   For hunks that **only delete lines** (no additions), `LINES_NEW` is `0`. (e.g., `@@ -25,3 +25,0 @@` means 3 lines deleted starting from original line 25).
*   **Hunk Content:**
    *   Lines prefixed with a space (` `) are context lines (unchanged).
    *   Lines prefixed with a minus (`-`) are lines removed from the original file.
    *   Lines prefixed with a plus (`+`) are lines added to the new file.
    *   Include at least 3 lines of unchanged context around changes, where available. If changes are at the very beginning or end of a file, or if hunks are very close, fewer context lines are acceptable as per standard unified diff practice.

### Specific Cases:
*   **Newly Created Files:**
    ```diff
    diff --git a/relative/path/to/new_file.ext b/relative/path/to/new_file.ext
    new file mode 100644
    index 0000000..<hash_new_placeholder>
    --- /dev/null
    +++ b/relative/path/to/new_file.ext
    @@ -0,0 +1,LINES_IN_NEW_FILE @@
    +line 1 of new file
    +line 2 of new file
    ...
    ```
    *(The `new file mode` and `index` lines should be included. Use `100644` for regular files. For the hash in the `index` line, a placeholder like `abcdef0` is acceptable if the actual hash cannot be computed.)*

*   **Deleted Files:**
    ```diff
    diff --git a/relative/path/to/deleted_file.ext b/relative/path/to/deleted_file.ext
    deleted file mode <mode_old_placeholder>
    index <hash_old_placeholder>..0000000
    --- a/relative/path/to/deleted_file.ext
    +++ /dev/null
    @@ -1,LINES_IN_OLD_FILE +0,0 @@
    -line 1 of old file
    -line 2 of old file
    ...
    ```
    *(The `deleted file mode` and `index` lines should be included. Use a placeholder like `100644` for mode and `abcdef0` for hash if actual values are unknown.)*

*   **Untouched Files:** Do NOT include any diff output for files that have no changes.

### General Constraints on Generated Code:
*   **Minimal & Precise Changes:** Generate the smallest, most targeted diff that correctly implements the `User Task` per all rules.
*   **Preserve Integrity:** Do not break existing functionality unless the `User Task` explicitly requires it. The codebase should remain buildable/runnable.
*   **Leverage Existing Code:** Prefer modifying existing files over creating new ones, unless a new file is architecturally justified or required by `User Task` or `User Rules`.

---

## 5. File Structure Format Description
The `File Structure` (provided in the next section) is formatted as follows:
1.  An initial project directory tree structure (e.g., generated by `tree` or similar).
2.  Followed by the content of each file, using an XML-like structure:
    <file path="RELATIVE/PATH/TO/FILE">
    (File content here)
    </file>
    The `path` attribute contains the project-root-relative path, using forward slashes (`/`).
    File content is the raw text of the file. Each file block is separated by a newline.

---

## 6. File Structure
foodster/
├── android
│   ├── app
│   │   ├── src
│   │   │   ├── debug
│   │   │   │   └── AndroidManifest.xml
│   │   │   ├── main
│   │   │   │   ├── java
│   │   │   │   │   └── io
│   │   │   │   │       └── flutter
│   │   │   │   │           └── plugins
│   │   │   │   │               └── GeneratedPluginRegistrant.java
│   │   │   │   ├── kotlin
│   │   │   │   │   └── com
│   │   │   │   │       └── example
│   │   │   │   │           └── foodster
│   │   │   │   │               └── MainActivity.kt
│   │   │   │   ├── res
│   │   │   │   │   ├── drawable
│   │   │   │   │   │   └── launch_background.xml
│   │   │   │   │   ├── drawable-v21
│   │   │   │   │   │   └── launch_background.xml
│   │   │   │   │   ├── mipmap-hdpi
│   │   │   │   │   ├── mipmap-mdpi
│   │   │   │   │   ├── mipmap-xhdpi
│   │   │   │   │   ├── mipmap-xxhdpi
│   │   │   │   │   ├── mipmap-xxxhdpi
│   │   │   │   │   ├── values
│   │   │   │   │   │   └── styles.xml
│   │   │   │   │   └── values-night
│   │   │   │   │       └── styles.xml
│   │   │   │   └── AndroidManifest.xml
│   │   │   └── profile
│   │   │       └── AndroidManifest.xml
│   │   └── build.gradle.kts
│   ├── gradle
│   │   └── wrapper
│   │       ├── gradle-wrapper.jar
│   │       └── gradle-wrapper.properties
│   ├── .gitignore
│   ├── build.gradle.kts
│   ├── gradle.properties
│   ├── gradlew
│   ├── gradlew.bat
│   ├── local.properties
│   └── settings.gradle.kts
├── assets
│   ├── fonts
│   ├── icons
│   └── images
├── docs
│   ├── COPILOT_INSTRUCTIONS.md
│   ├── DESIGN_SPECS.md
│   ├── DESIGN_SYSTEM.json
│   ├── IMPLEMENTATION_STATUS.md
│   └── PROJECT_REQUIREMENTS.md
├── ios
│   ├── .symlinks
│   │   └── plugins
│   │       ├── app_links
│   │       ├── connectivity_plus
│   │       ├── flutter_native_splash
│   │       ├── flutter_secure_storage
│   │       ├── google_maps_flutter_ios
│   │       ├── path_provider_foundation
│   │       ├── shared_preferences_foundation
│   │       ├── sqflite_darwin
│   │       └── url_launcher_ios
│   ├── Flutter
│   │   ├── ephemeral
│   │   │   ├── flutter_lldb_helper.py
│   │   │   └── flutter_lldbinit
│   │   ├── AppFrameworkInfo.plist
│   │   ├── Debug.xcconfig
│   │   ├── Flutter.podspec
│   │   ├── flutter_export_environment.sh
│   │   ├── Generated.xcconfig
│   │   └── Release.xcconfig
│   ├── Runner
│   │   ├── Assets.xcassets
│   │   │   ├── AppIcon.appiconset
│   │   │   │   └── Contents.json
│   │   │   └── LaunchImage.imageset
│   │   │       ├── Contents.json
│   │   │       └── README.md
│   │   ├── Base.lproj
│   │   │   ├── LaunchScreen.storyboard
│   │   │   └── Main.storyboard
│   │   ├── AppDelegate.swift
│   │   ├── GeneratedPluginRegistrant.h
│   │   ├── GeneratedPluginRegistrant.m
│   │   ├── Info.plist
│   │   └── Runner-Bridging-Header.h
│   ├── Runner.xcodeproj
│   │   ├── project.xcworkspace
│   │   │   ├── xcshareddata
│   │   │   │   ├── swiftpm
│   │   │   │   │   └── configuration
│   │   │   │   ├── IDEWorkspaceChecks.plist
│   │   │   │   └── WorkspaceSettings.xcsettings
│   │   │   ├── xcuserdata
│   │   │   │   └── varyable.xcuserdatad
│   │   │   │       └── UserInterfaceState.xcuserstate
│   │   │   └── contents.xcworkspacedata
│   │   ├── xcshareddata
│   │   │   └── xcschemes
│   │   │       └── Runner.xcscheme
│   │   └── project.pbxproj
│   ├── Runner.xcworkspace
│   │   ├── xcshareddata
│   │   │   ├── swiftpm
│   │   │   │   └── configuration
│   │   │   ├── IDEWorkspaceChecks.plist
│   │   │   └── WorkspaceSettings.xcsettings
│   │   ├── xcuserdata
│   │   │   └── varyable.xcuserdatad
│   │   │       └── UserInterfaceState.xcuserstate
│   │   └── contents.xcworkspacedata
│   ├── RunnerTests
│   │   └── RunnerTests.swift
│   ├── .gitignore
│   ├── Podfile
│   └── Podfile.lock
├── lib
│   ├── core
│   │   ├── config
│   │   │   └── app_config.dart
│   │   ├── constants
│   │   │   ├── app_constants.dart
│   │   │   └── message_constants.dart
│   │   ├── di
│   │   │   └── injection_container.dart
│   │   ├── error
│   │   │   └── failure.dart
│   │   ├── errors
│   │   │   ├── exceptions.dart
│   │   │   └── failures.dart
│   │   ├── network
│   │   │   └── supabase_service.dart
│   │   ├── routes
│   │   │   └── app_router.dart
│   │   ├── theme
│   │   │   └── app_theme.dart
│   │   ├── usecases
│   │   │   └── usecase.dart
│   │   ├── utils
│   │   └── widgets
│   │       ├── keyboard_dismissible.dart
│   │       └── loading_indicator.dart
│   ├── features
│   │   ├── auth
│   │   │   ├── data
│   │   │   │   ├── datasources
│   │   │   │   ├── models
│   │   │   │   └── repositories
│   │   │   ├── domain
│   │   │   │   ├── entities
│   │   │   │   ├── repositories
│   │   │   │   └── usecases
│   │   │   └── presentation
│   │   │       ├── bloc
│   │   │       ├── pages
│   │   │       │   ├── login_page.dart
│   │   │       │   ├── signup_page.dart
│   │   │       │   └── splash_page.dart
│   │   │       └── widgets
│   │   │           └── onboarding_slide.dart
│   │   ├── budget_tracking
│   │   │   ├── data
│   │   │   │   ├── models
│   │   │   │   │   └── budget_model.dart
│   │   │   │   └── repositories
│   │   │   │       └── budget_repository_impl.dart
│   │   │   ├── domain
│   │   │   │   ├── entities
│   │   │   │   │   └── budget.dart
│   │   │   │   ├── repositories
│   │   │   │   │   └── budget_repository.dart
│   │   │   │   └── usecases
│   │   │   │       └── budget_usecases.dart
│   │   │   └── presentation
│   │   │       ├── bloc
│   │   │       │   ├── budget_bloc.dart
│   │   │       │   ├── budget_event.dart
│   │   │       │   └── budget_state.dart
│   │   │       ├── pages
│   │   │       │   ├── add_expense_page.dart
│   │   │       │   ├── budget_page.dart
│   │   │       │   └── create_budget_page.dart
│   │   │       └── widgets
│   │   │           ├── budget_category_tile.dart
│   │   │           └── budget_overview_card.dart
│   │   ├── dashboard
│   │   │   └── presentation
│   │   │       ├── pages
│   │   │       │   └── dashboard_page.dart
│   │   │       └── widgets
│   │   │           └── dashboard_home.dart
│   │   ├── grocery_list
│   │   │   └── presentation
│   │   │       └── pages
│   │   │           └── grocery_list_page.dart
│   │   ├── meal_planning
│   │   │   └── presentation
│   │   │       ├── pages
│   │   │       │   └── meal_plan_page.dart
│   │   │       └── widgets
│   │   │           └── meal_card.dart
│   │   ├── nutrition
│   │   │   ├── data
│   │   │   │   ├── models
│   │   │   │   │   └── nutrition_model.dart
│   │   │   │   ├── repositories
│   │   │   │   │   └── nutrition_repository_impl.dart
│   │   │   │   └── services
│   │   │   │       └── nutrition_service.dart
│   │   │   ├── domain
│   │   │   │   ├── repositories
│   │   │   │   │   └── nutrition_repository.dart
│   │   │   │   └── usecases
│   │   │   │       └── get_nutrition_info.dart
│   │   │   └── presentation
│   │   │       ├── bloc
│   │   │       │   ├── nutrition_bloc.dart
│   │   │       │   ├── nutrition_event.dart
│   │   │       │   └── nutrition_state.dart
│   │   │       ├── pages
│   │   │       │   └── nutrition_page.dart
│   │   │       └── widgets
│   │   │           ├── nutrition_bottom_sheet.dart
│   │   │           └── nutrition_info_widget.dart
│   │   ├── onboarding
│   │   │   ├── data
│   │   │   │   ├── models
│   │   │   │   └── repositories
│   │   │   │       └── user_profile_repository.dart
│   │   │   ├── domain
│   │   │   │   └── entities
│   │   │   │       └── user_profile.dart
│   │   │   └── presentation
│   │   │       ├── pages
│   │   │       │   ├── allergies_page.dart
│   │   │       │   ├── dietary_preferences_page.dart
│   │   │       │   ├── household_page.dart
│   │   │       │   ├── nutrition_goals_page.dart
│   │   │       │   ├── onboarding_page.dart
│   │   │       │   ├── splash_page.dart
│   │   │       │   ├── summary_page.dart
│   │   │       │   └── welcome_page.dart
│   │   │       └── widgets
│   │   │           ├── onboarding_button.dart
│   │   │           ├── onboarding_progress.dart
│   │   │           ├── selection_card.dart
│   │   │           └── selection_chip.dart
│   │   ├── profile
│   │   │   └── presentation
│   │   │       ├── pages
│   │   │       │   └── profile_page.dart
│   │   │       └── widgets
│   │   │           └── auth_section.dart
│   │   └── recipes
│   │       ├── data
│   │       │   ├── models
│   │       │   │   └── recipe_model.dart
│   │       │   └── repositories
│   │       │       └── recipe_repository_impl.dart
│   │       ├── domain
│   │       │   ├── entities
│   │       │   │   └── recipe.dart
│   │       │   ├── repositories
│   │       │   │   └── recipe_repository.dart
│   │       │   └── usecases
│   │       │       └── recipe_usecases.dart
│   │       └── presentation
│   │           ├── bloc
│   │           │   ├── recipe_detail_bloc.dart
│   │           │   ├── recipe_detail_event.dart
│   │           │   └── recipe_detail_state.dart
│   │           └── pages
│   │               └── recipe_detail_page.dart
│   ├── main.dart
│   └── mcp_setup.dart
├── linux
│   ├── flutter
│   │   ├── ephemeral
│   │   │   └── .plugin_symlinks
│   │   │       ├── app_links_linux
│   │   │       ├── connectivity_plus
│   │   │       ├── flutter_secure_storage_linux
│   │   │       ├── gtk
│   │   │       ├── path_provider_linux
│   │   │       ├── shared_preferences_linux
│   │   │       └── url_launcher_linux
│   │   ├── CMakeLists.txt
│   │   ├── generated_plugin_registrant.cc
│   │   ├── generated_plugin_registrant.h
│   │   └── generated_plugins.cmake
│   ├── runner
│   │   ├── CMakeLists.txt
│   │   ├── main.cc
│   │   ├── my_application.cc
│   │   └── my_application.h
│   ├── .gitignore
│   └── CMakeLists.txt
├── macos
│   ├── Flutter
│   │   ├── ephemeral
│   │   │   ├── Flutter-Generated.xcconfig
│   │   │   └── flutter_export_environment.sh
│   │   ├── Flutter-Debug.xcconfig
│   │   ├── Flutter-Release.xcconfig
│   │   └── GeneratedPluginRegistrant.swift
│   ├── Runner
│   │   ├── Assets.xcassets
│   │   │   └── AppIcon.appiconset
│   │   │       └── Contents.json
│   │   ├── Base.lproj
│   │   │   └── MainMenu.xib
│   │   ├── Configs
│   │   │   ├── AppInfo.xcconfig
│   │   │   ├── Debug.xcconfig
│   │   │   ├── Release.xcconfig
│   │   │   └── Warnings.xcconfig
│   │   ├── AppDelegate.swift
│   │   ├── DebugProfile.entitlements
│   │   ├── Info.plist
│   │   ├── MainFlutterWindow.swift
│   │   └── Release.entitlements
│   ├── Runner.xcodeproj
│   │   ├── project.xcworkspace
│   │   │   └── xcshareddata
│   │   │       ├── swiftpm
│   │   │       │   └── configuration
│   │   │       └── IDEWorkspaceChecks.plist
│   │   ├── xcshareddata
│   │   │   └── xcschemes
│   │   │       └── Runner.xcscheme
│   │   └── project.pbxproj
│   ├── Runner.xcworkspace
│   │   ├── xcshareddata
│   │   │   ├── swiftpm
│   │   │   │   └── configuration
│   │   │   └── IDEWorkspaceChecks.plist
│   │   └── contents.xcworkspacedata
│   ├── RunnerTests
│   │   └── RunnerTests.swift
│   ├── .gitignore
│   └── Podfile
├── supabase
│   ├── .branches
│   │   └── _current_branch
│   ├── functions
│   │   └── get-nutrition
│   │       ├── index.ts
│   │       └── mock-data.ts
│   ├── .gitignore
│   └── config.toml
├── test
│   ├── api
│   └── widget_test.dart
├── web
│   ├── icons
│   ├── index.html
│   └── manifest.json
├── windows
│   ├── flutter
│   │   ├── ephemeral
│   │   │   └── .plugin_symlinks
│   │   │       ├── app_links
│   │   │       ├── connectivity_plus
│   │   │       ├── flutter_secure_storage_windows
│   │   │       ├── path_provider_windows
│   │   │       ├── shared_preferences_windows
│   │   │       └── url_launcher_windows
│   │   ├── CMakeLists.txt
│   │   ├── generated_plugin_registrant.cc
│   │   ├── generated_plugin_registrant.h
│   │   └── generated_plugins.cmake
│   ├── runner
│   │   ├── resources
│   │   ├── CMakeLists.txt
│   │   ├── flutter_window.cpp
│   │   ├── flutter_window.h
│   │   ├── main.cpp
│   │   ├── resource.h
│   │   ├── runner.exe.manifest
│   │   ├── Runner.rc
│   │   ├── utils.cpp
│   │   ├── utils.h
│   │   ├── win32_window.cpp
│   │   └── win32_window.h
│   ├── .gitignore
│   └── CMakeLists.txt
├── .env.local.example
├── .flutter-plugins-dependencies
├── .gitignore
├── .metadata
├── analysis_options.yaml
├── devtools_options.yaml
├── foodster.iml
├── pubspec.lock
├── pubspec.yaml
└── README.md

<file path="android/app/src/debug/AndroidManifest.xml">
<manifest xmlns:android="http://schemas.android.com/apk/res/android">
    <!-- The INTERNET permission is required for development. Specifically,
         the Flutter tool needs it to communicate with the running application
         to allow setting breakpoints, to provide hot reload, etc.
    -->
    <uses-permission android:name="android.permission.INTERNET"/>
</manifest>

</file>
<file path="android/app/src/main/java/io/flutter/plugins/GeneratedPluginRegistrant.java">
package io.flutter.plugins;

import androidx.annotation.Keep;
import androidx.annotation.NonNull;
import io.flutter.Log;

import io.flutter.embedding.engine.FlutterEngine;

/**
 * Generated file. Do not edit.
 * This file is generated by the Flutter tool based on the
 * plugins that support the Android platform.
 */
@Keep
public final class GeneratedPluginRegistrant {
  private static final String TAG = "GeneratedPluginRegistrant";
  public static void registerWith(@NonNull FlutterEngine flutterEngine) {
    try {
      flutterEngine.getPlugins().add(new com.llfbandit.app_links.AppLinksPlugin());
    } catch (Exception e) {
      Log.e(TAG, "Error registering plugin app_links, com.llfbandit.app_links.AppLinksPlugin", e);
    }
    try {
      flutterEngine.getPlugins().add(new dev.fluttercommunity.plus.connectivity.ConnectivityPlugin());
    } catch (Exception e) {
      Log.e(TAG, "Error registering plugin connectivity_plus, dev.fluttercommunity.plus.connectivity.ConnectivityPlugin", e);
    }
    try {
      flutterEngine.getPlugins().add(new net.jonhanson.flutter_native_splash.FlutterNativeSplashPlugin());
    } catch (Exception e) {
      Log.e(TAG, "Error registering plugin flutter_native_splash, net.jonhanson.flutter_native_splash.FlutterNativeSplashPlugin", e);
    }
    try {
      flutterEngine.getPlugins().add(new io.flutter.plugins.flutter_plugin_android_lifecycle.FlutterAndroidLifecyclePlugin());
    } catch (Exception e) {
      Log.e(TAG, "Error registering plugin flutter_plugin_android_lifecycle, io.flutter.plugins.flutter_plugin_android_lifecycle.FlutterAndroidLifecyclePlugin", e);
    }
    try {
      flutterEngine.getPlugins().add(new com.it_nomads.fluttersecurestorage.FlutterSecureStoragePlugin());
    } catch (Exception e) {
      Log.e(TAG, "Error registering plugin flutter_secure_storage, com.it_nomads.fluttersecurestorage.FlutterSecureStoragePlugin", e);
    }
    try {
      flutterEngine.getPlugins().add(new io.flutter.plugins.googlemaps.GoogleMapsPlugin());
    } catch (Exception e) {
      Log.e(TAG, "Error registering plugin google_maps_flutter_android, io.flutter.plugins.googlemaps.GoogleMapsPlugin", e);
    }
    try {
      flutterEngine.getPlugins().add(new io.flutter.plugins.pathprovider.PathProviderPlugin());
    } catch (Exception e) {
      Log.e(TAG, "Error registering plugin path_provider_android, io.flutter.plugins.pathprovider.PathProviderPlugin", e);
    }
    try {
      flutterEngine.getPlugins().add(new io.flutter.plugins.sharedpreferences.SharedPreferencesPlugin());
    } catch (Exception e) {
      Log.e(TAG, "Error registering plugin shared_preferences_android, io.flutter.plugins.sharedpreferences.SharedPreferencesPlugin", e);
    }
    try {
      flutterEngine.getPlugins().add(new com.tekartik.sqflite.SqflitePlugin());
    } catch (Exception e) {
      Log.e(TAG, "Error registering plugin sqflite_android, com.tekartik.sqflite.SqflitePlugin", e);
    }
    try {
      flutterEngine.getPlugins().add(new io.flutter.plugins.urllauncher.UrlLauncherPlugin());
    } catch (Exception e) {
      Log.e(TAG, "Error registering plugin url_launcher_android, io.flutter.plugins.urllauncher.UrlLauncherPlugin", e);
    }
  }
}

</file>
<file path="android/app/src/main/kotlin/com/example/foodster/MainActivity.kt">
package com.example.foodster

import io.flutter.embedding.android.FlutterActivity

class MainActivity : FlutterActivity()

</file>
<file path="android/app/src/main/res/drawable/launch_background.xml">
<?xml version="1.0" encoding="utf-8"?>
<!-- Modify this file to customize your launch splash screen -->
<layer-list xmlns:android="http://schemas.android.com/apk/res/android">
    <item android:drawable="@android:color/white" />

    <!-- You can insert your own image assets here -->
    <!-- <item>
        <bitmap
            android:gravity="center"
            android:src="@mipmap/launch_image" />
    </item> -->
</layer-list>

</file>
<file path="android/app/src/main/res/drawable-v21/launch_background.xml">
<?xml version="1.0" encoding="utf-8"?>
<!-- Modify this file to customize your launch splash screen -->
<layer-list xmlns:android="http://schemas.android.com/apk/res/android">
    <item android:drawable="?android:colorBackground" />

    <!-- You can insert your own image assets here -->
    <!-- <item>
        <bitmap
            android:gravity="center"
            android:src="@mipmap/launch_image" />
    </item> -->
</layer-list>

</file>
<file path="android/app/src/main/res/values/styles.xml">
<?xml version="1.0" encoding="utf-8"?>
<resources>
    <!-- Theme applied to the Android Window while the process is starting when the OS's Dark Mode setting is off -->
    <style name="LaunchTheme" parent="@android:style/Theme.Light.NoTitleBar">
        <!-- Show a splash screen on the activity. Automatically removed when
             the Flutter engine draws its first frame -->
        <item name="android:windowBackground">@drawable/launch_background</item>
    </style>
    <!-- Theme applied to the Android Window as soon as the process has started.
         This theme determines the color of the Android Window while your
         Flutter UI initializes, as well as behind your Flutter UI while its
         running.

         This Theme is only used starting with V2 of Flutter's Android embedding. -->
    <style name="NormalTheme" parent="@android:style/Theme.Light.NoTitleBar">
        <item name="android:windowBackground">?android:colorBackground</item>
    </style>
</resources>

</file>
<file path="android/app/src/main/res/values-night/styles.xml">
<?xml version="1.0" encoding="utf-8"?>
<resources>
    <!-- Theme applied to the Android Window while the process is starting when the OS's Dark Mode setting is on -->
    <style name="LaunchTheme" parent="@android:style/Theme.Black.NoTitleBar">
        <!-- Show a splash screen on the activity. Automatically removed when
             the Flutter engine draws its first frame -->
        <item name="android:windowBackground">@drawable/launch_background</item>
    </style>
    <!-- Theme applied to the Android Window as soon as the process has started.
         This theme determines the color of the Android Window while your
         Flutter UI initializes, as well as behind your Flutter UI while its
         running.

         This Theme is only used starting with V2 of Flutter's Android embedding. -->
    <style name="NormalTheme" parent="@android:style/Theme.Black.NoTitleBar">
        <item name="android:windowBackground">?android:colorBackground</item>
    </style>
</resources>

</file>
<file path="android/app/src/main/AndroidManifest.xml">
<manifest xmlns:android="http://schemas.android.com/apk/res/android">
    <application
        android:label="foodster"
        android:name="${applicationName}"
        android:icon="@mipmap/ic_launcher">
        <activity
            android:name=".MainActivity"
            android:exported="true"
            android:launchMode="singleTop"
            android:taskAffinity=""
            android:theme="@style/LaunchTheme"
            android:configChanges="orientation|keyboardHidden|keyboard|screenSize|smallestScreenSize|locale|layoutDirection|fontScale|screenLayout|density|uiMode"
            android:hardwareAccelerated="true"
            android:windowSoftInputMode="adjustResize">
            <!-- Specifies an Android theme to apply to this Activity as soon as
                 the Android process has started. This theme is visible to the user
                 while the Flutter UI initializes. After that, this theme continues
                 to determine the Window background behind the Flutter UI. -->
            <meta-data
              android:name="io.flutter.embedding.android.NormalTheme"
              android:resource="@style/NormalTheme"
              />
            <intent-filter>
                <action android:name="android.intent.action.MAIN"/>
                <category android:name="android.intent.category.LAUNCHER"/>
            </intent-filter>
        </activity>
        <!-- Don't delete the meta-data below.
             This is used by the Flutter tool to generate GeneratedPluginRegistrant.java -->
        <meta-data
            android:name="flutterEmbedding"
            android:value="2" />
    </application>
    <!-- Required to query activities that can process text, see:
         https://developer.android.com/training/package-visibility and
         https://developer.android.com/reference/android/content/Intent#ACTION_PROCESS_TEXT.

         In particular, this is used by the Flutter engine in io.flutter.plugin.text.ProcessTextPlugin. -->
    <queries>
        <intent>
            <action android:name="android.intent.action.PROCESS_TEXT"/>
            <data android:mimeType="text/plain"/>
        </intent>
    </queries>
</manifest>

</file>
<file path="android/app/src/profile/AndroidManifest.xml">
<manifest xmlns:android="http://schemas.android.com/apk/res/android">
    <!-- The INTERNET permission is required for development. Specifically,
         the Flutter tool needs it to communicate with the running application
         to allow setting breakpoints, to provide hot reload, etc.
    -->
    <uses-permission android:name="android.permission.INTERNET"/>
</manifest>

</file>
<file path="android/app/build.gradle.kts">
plugins {
    id("com.android.application")
    id("kotlin-android")
    // The Flutter Gradle Plugin must be applied after the Android and Kotlin Gradle plugins.
    id("dev.flutter.flutter-gradle-plugin")
}

android {
    namespace = "com.example.foodster"
    compileSdk = flutter.compileSdkVersion
    ndkVersion = flutter.ndkVersion

    compileOptions {
        sourceCompatibility = JavaVersion.VERSION_11
        targetCompatibility = JavaVersion.VERSION_11
    }

    kotlinOptions {
        jvmTarget = JavaVersion.VERSION_11.toString()
    }

    defaultConfig {
        // TODO: Specify your own unique Application ID (https://developer.android.com/studio/build/application-id.html).
        applicationId = "com.example.foodster"
        // You can update the following values to match your application needs.
        // For more information, see: https://flutter.dev/to/review-gradle-config.
        minSdk = flutter.minSdkVersion
        targetSdk = flutter.targetSdkVersion
        versionCode = flutter.versionCode
        versionName = flutter.versionName
    }

    buildTypes {
        release {
            // TODO: Add your own signing config for the release build.
            // Signing with the debug keys for now, so `flutter run --release` works.
            signingConfig = signingConfigs.getByName("debug")
        }
    }
}

flutter {
    source = "../.."
}

</file>
<file path="android/gradle/wrapper/gradle-wrapper.jar">
PK
   *��G           	   META-INF/ PK
   *��Gו�R?   U      META-INF/MANIFEST.MF�M��LK-.�
K-*��ϳR0�3����-�I�M�+I,
��d���Z)�%���b�µ���r PK
   ��G              org/ PK
   ��G              org/gradle/ PK
   ��G              org/gradle/wrapper/ PK
   ��Gh�df�   �   #   org/gradle/wrapper/Download$1.class}�M
�0��h5Z+v/�׆��p!.<AlCl	II������q�<�=�|���	 C��bB|�7��}�%a����V�J�a�3���4�(��-&��u�+'
-y�D]K��� Br0F���KOH;��(~�T2o�?�t=|���"BF���
u-} PK
   ��G�ޅ�  p  D   org/gradle/wrapper/Download$SystemPropertiesProxyAuthenticator.class�SmoA~(�����`����zT��o�`	��	1M�ML?mag�wdo�����m4�����@� ����<��<;������ �xj"��	k)�6���6SX��[�k"����{�^��]��S<dH�¦`X�{�x�=9�-?�)b������~L��1��#%N�e�Ry""��_������*��� ���(�yYe�mI���ۓ�CTw/�~ț��V�[B�S�^(�c�N����n �;=�b�>H�3SK��;�a(�Id0®l�W�O�*i)2X�sd1,����:���"!
<�P�Kw���jd�@��C<�x°��e��V9�Μ Cv@�y�r�}������[N},EI/hU���MI�Gjv2F"TXksY�����S<�1䧴8���^�qL��_fAq����#Z��K�^���LNYZ�� �eZ�al\#�@��JQ���!��N�c�N��9R_p�槉rb�\~X��n��='iaӵ��	#-�[��mu�W����Q���N��/PK
   ��G��Xs�   �   "   org/gradle/wrapper/IDownload.classE��
�0������
^�b���
AP�^�26J;�t>���;�ɗ���|� �{�z~�+%5O��&�WΔ(�a�_�4[gR��#!X�bQ��Vg=�{}1����A��Y��C�X����'R�����5��c/�J�����$����S�@pP��\�mKu���l��PK
   ��G�z�\  Q  -   org/gradle/wrapper/GradleUserHomeLookup.class�S[OA�F�]��R�(��j[[�Z�U��˪�T 	Od�	�.�dYl�W�$jj��>�G5=�R+ȃ�ɹ�w�s����??~Xª�Q��x��)�I�)`^F\��F� Ṃ� �zQFRhM�K	K�[���A*_�ɮo���ANϖvӟt�p��854˰�Z�sM���0ݍ+e�錞�K{z�ahӱ�a{�jr⿅��>4�fڦ���?�(06�
%��L��7k����}8e�*�)�v0��
�D�q�Z�5*�>�F��]m��4����x�qN�uj}��g�'�-���mZ�0�Z�jw�䜦[�b�!ڋ3)�UD0A\>y�I��A$�R���f
M�x��f����FӴ*�e]ӫx�wԯ���x��wu��H�𘗽�P�����`�{�!��!�}%�n�x/ �q���}J�hͮ0,މ�= q@�����{��,��Qz���i��i�G�7� !�8C���H3�`_[�(�`+8�$U�)<$�4��OZd�4�}��/���z�@�������:C��Y�ׅ"D"V�v�I� ����(&�%�꿮�)[|SW/����9���s�,��n�%Br�Uv�/PK
   ��G�]��  �
  3   org/gradle/wrapper/ExclusiveFileAccessManager.class�VKpe�m�&i�m!@!Pl ��P�(ӂ��bhZ�
���6]�ݍ�MK} >��u�蝣2#-X�GGǃ:�����/�/�߷���������6?���
 �� �������Gw^��D>t��Iq�	�W�iA9%�� N�ߏ� �Q#�58�3x6HS�q���0(3d��D-�*��p�Wb��~~�֤�':�u$S��zOut$�%�R��1%�+F.�k[��k�P�0���v��U	�6��E�� AJJ�Λ��4l�StA��j�f�PM&�$xf��5)�P������VuU�33�ާX����^{X��Xʴr���du56n)��j��/d�bAS;4]m�d�B�K1��j�Oqڢn�r-��hkz,c��e��K(�.<�4̋���y5cӘ�![v����nfF$Թ�
g��P����a��L���13a�Д��� 	��5��R��9���6���Y��K���,A�p/��]����l
\ak:�r��A�C���}ѫ��.Z��jMǗf��}��u��U��QebPM�fA=Y�T[� �{��[K���8L�b�<sHi�"3+/a�2FX�Y�2�2֯�u{���(�2�N��S�<?J/���q�d)jzV�d`�(b���Wό��(.Ș��~�$�e�"a�JE����e\ī~\��^���S~�!�ML�_���)b|��!�2��s��oq�"��d��ld\�lb��Vd��#��El32D�q���B~Ǐwe�����@Ƈ��儰F�g��)�����q�➒P5�ʩ�	E�Oij�
���O�ne���ц�:��od�*���T�T@1����$�X��::�լ��̂�z'
�:ʆ/M�(N����+̧(�0�#�m6�U�|��̋mM��������y@���檼��*�����E��e�����^���=�].�}��Wz������s�����`����J�F�.�}�<�XG���v� �D�<"{FILE_STRUCTURE}���vGBb�7pm�m�2e�k�!5n�
Oc�U�]q�w;j^�۸nG
W��R"���pN䜄#�1���x�����3���)���70
?��)T��\�P9
����)T���^)^~
��>)�G/���/��T>��3�h
���ڸ7��B(^��:G���a*�{�P�l�6\�;#�4͠v� �����)l
�y&�ͳ`���su�a���{FILE_STRUCTURE}�&��s��"\^&x3^9O��&3�9�q����ߝ}~������9���z����(s��L7R�	���<��7���o��:�ǐG3�ă��VL�=��W_�ߠ��(~���׉_h�y�����?����;��C��r�|�����1C_���J�V���,9�f�x��6'��2�!�Ƽ��!��0��9�'(�$)^R��V���-��=�����w�j��$\�7u�d/����j-�'I�%���4۵�	��2����ۤG*1 ��<�PK
   ��G�
^F�  �  -   org/gradle/wrapper/WrapperConfiguration.class��mOA����փ>����"�!���3	U1UH|C�p�#���]5�Si"1���e��[����73�3����\����_ ��(�K��`6���q?��<7��y��B�i<d��m?��V7�]�a�qh~2
�
����������[���c:m�I5�-��3����T���_Xlv��_��h����3۱�%�x��͐Xu���o؎��{Բ��f���mӳ�Y$���3���6ڞ�߱�Ϟy|ly�N�W]����z&g!�|�
�.��P��o��rS�z�����Q��*��.��쫚�����FG��gD���g�#~�R�TN?G��=D�h���C��z{���~T��Y�
�^_n.�5��[���7u
��3�~!�+�5��P��̍�
E�8��_��:_�F���0�� R�������/_�$|9�`���0�v(�ȏ�?��R�q��8A2�Sa���/�F�l	�r�)`��1�PV�Qf�<�\��y-�G��ٰ����g�q\�%���n}��s�\�� ��p�p�%&�H��p7 qdR	2A� q�F��0��[J��2���I� s���R �J�������� �I��HQ�L)AR2�s%HU
��A�I���$@jJ��RW��j���N��}@�d:���PK
   ��GQ}i�  
  0   org/gradle/wrapper/SystemPropertiesHandler.class�V�sU�m�d�vˣPJJ�i���l
*�J�$P,��Mo��d7l6���F����W>9�����w�(�w7�͋�fz���u���s���������Y�.��xCCF4�Ĩ�75�%��c�0.w*&U�V���V�qF�	I�JrN��� �kx���B���\»��'/;'��k00#IR�,�T�kA
i���
C�e�G4G����۳B��1����p�3r��중�2S��L��6�
z�l'K9�lF��#�N,��wE��c���"Ұ(v�
6��[+U�32vٸf�L;6bfD��t,�f&6n�h4���#� T�;j�
nID5���/�G'��"皶EY��(s���J�hfZ)�Ԭ�&��Yv��숔X���H�`8�BVXn��ə�"�R�d��`K��QW8�kK����te�dTg�����Ȅej�r�m�� )3�.o�er�$�K���.��u��ڡz��9G�p]�LY�[p�cx�b�٧��NR���zFK�KS�A�7��s�ȧy��+:2`�li����g���h鰑�qU��ܠ�t�~�`�fVT;j�:V03���v;������
glc���Ju�s���yI\\��+ؘ_��b�?ڭbA�">�q��H�Ǹ����Y|��u�*��qSr���*���-���=~PqKǏ�IAt�������K���.X�ڮ�G&�`ۊ��5K
2c��3�EݾH}�tױ�H�k��/u5��k�Xl�H�"5�
"���M;��|L^��x�|�I;���Y%���7�9����R�,
[�rn�g}�z�lj��2����t᳼����w�2��i�I��a%E�VFi.�O�oΔu����9�mX���<��V�M{
7J�������/�Q�7��و
70_�C<��.ȿ&(r>���)�U���>���'�K��۱�T/) �n��E�h�<�A��XB��C4�E�����%���/A����Do_���݃>蓛���=�'��O/�����6��.6������my����"�>F��A_�����	�������,b�` (�����"�TCj;'��J��T|�p3����Q�}4�}�g�x�[C8�A�P"A�B;����&ꇈ�.J�����ziE����\"����&?�nq���A��K�O�8��:�a�N�>�G�b��F'-����&=�e��h=��q
/ӋO�NO�p
�7�g�B�C8��\���Q�J��X�z��c^��sw��
�PK
   ��G�y0�V        org/gradle/wrapper/Logger.class���o�Pǿ��*�1�pL��
EW�o1&�d		f&�����Ki�������������
X����s�9��*���
�O�H�f�p+�ۨ&Q�,D]�;Bܕp A��`���w�	��3�2����I��^m{��]�⯧�>wީ}�N6����Ǫc;8���Ƅ�Ե]�u`r壣�F�Q���s��5m��P힩T�T-]鹎a�-�P��Dս+T��Y��b|�Tuz|<���[�����(N$�&c�bXC3����R�Wu��:��+m���f=m�dϞ:?2��R�lDL��BZB3�{�/�AQH��GB<�	�	�2����o�g\���+�j���/��>M\>�{JQ��V��
\ZW��H��c��HX�Š\������_�)�G�t�
}�	Р����F���If�:B�~@R�	&�~ ���6H�#J�2b�&J9�밥6<MPi��B!`��Y�;"����h}��2�	eү.��sp�N�=p;�U ��������,�+��N!�����J���Z�.c�����@�wgX[�W	^#H݃�~���Bۥ�"��GZ�Kx-����j:��5(��K�:������PK
   ��G�r��  n  &   org/gradle/wrapper/PathAssembler.class�V�cW���y�8�sص�b'�,�IS(I��D���*Nڸkk#o*ﺻ�Ćr(�}��Z)Ĵ~����][�*���7�i��73ߛ�����7�ƿ�8����Rn�)� CQ��(Lٶd�ŋq�Q�[W�RQܔ�[2�F��A|$���g�R���q|���Sq|���gE���d�|���_���Q|)�/�х���UQ��_����1|K�o�������}�q'���C9y'��
�X�Zq�p\�X(��e*Ps��ۃE�qtG��K��C#��3SÓ���G�秦'sc�$Fnh7�lQ3�)0���L��LwV+�t�&&ǟ��:��`k��>���ekY4|3��}�(�4y�0
����T���Р�������J��=�-u�/�g5ېu�r�����B�w��ek++����ܥ~F�LM���v�o���3��z���gF{�(�lMt�w���oI��lq�u��u� ��9��&���i��l�b����\�cE�x���{q֩|�d/k�%�З������˺�h}�(莫��Wr�Œm�k���S?���
;�(P��mV�W�� ?�s�����uS^uu���2��(̆�c΂��9�����������s�.n�xS��WXvq`���w*�CRP��J��.�d���	pQ�y<ŏT��*��5sxZ�S�E�nY�O���,�Jע(żn�����?gF�*~!����_��k���̻g���M�U1��Y��N�$K�\E1�pC_t���������C�F�GU��U�_�xG��.;`r���k�p�Ⲃ�u�����;Y2]cY��g��U*擦�&�Hܤa��ܤO�>�]aF(���T�ǆ��KF����
�Ir����fח7�(�����In
3�%I���$K��u�`�o�b���M%�Tw-5ִ��p-���U��hWf^A�;����]��Tp!�j�oC��b��1Cݾ�������۫�S����(#�-��S��:�^��p�>���if~��y��"c<�{Fw��1�2~C���%FJ+yͥ�p���D��"���s(��6]�e��L/��-yk�6�3������u1Y;���)-l��é\}��j��_,iE���f����Ӛ���~'l�p�v2����:�o�F���x�*W
�p}nۺ�?vV�1���r� W�pV8��(�z���t ���~J���rC��'�)�q1�μ����}�$i�8��ӳt��,�$��0m�6�KOQηә2+��{*��{3D΅6���b��@|��&��2�&�qh
q(c���v�&v�zRO�Pd�ҭ��H-�[��!�1�P��ia�уUfWB����
�6.q�\�Kȱ
<uORj�Y�O�cL� �G��0���(�5��ɸ�B�������CK	���s����P��=��ה�4Kv�C��Oo�:�!P<I�5P3�qU��g�߻<''�m���(��>��[F��ͅ��=$�O�q|}t]s�������,v玬�s@14�:�,v�-��2��1�)�hbf�1C,]�g��M��>|�C� _�k���d�q�B�]�3����2Rռ$�h�b�Fhy����'��[Zp����U<�ћ�!�/��b�����机�A�=�=���HW=fd`TaF�2�fƓ �jUڶ��a��G��y\Qy}ĿT��8���yN22�IA��ү�a=���V�B�D�=d+xﺔ%�|�4�������#j�>K�>G��X�y� ����B:�|P�;��XǇ��|�� PK
   ��G8޶�  �)      org/gradle/wrapper/Install.class�Y|��?'���,�+��kD"y�7
I����0 �dw���#�ΒD�Z�JվP[�V��b-UQI�(�j}�������Vko���������*�fv7�d�/��f�{�����|_^���G�h!�>Η�'�"�_��4i�ɧ�2�����(�el�4�\�S���"��;�X��
4�g���
���� �aM��t�
�<OzJ�9S�����\�e��r����R��q\�5
/��B�Ɍ���K�����s��{����b���
y�,�sxU����x�Sxu������i��Q(�
��y��4)|��s���IGK�����l���Q�� ���~�$���TxoU�"��dҶ ���j����V����R��L�*�� �q�¡ m�v?��l��C��,�.6E����S�&�p,@�$��8��+�I(��Nn�ƒ�)�w)��n��^Y�'�^���j��Z�>->�Z�k��
�k�67o����u�Ʀ՛75�o�tCݦ�L�͗�������i�f�s���x,i�1k�IA�k�|�xg��`��Ot�t&�pĨ�I���F����J8����0�l��M
�q�ҭ[]uɤm���n6����ӌ�P$�4wk̈Q
��:=�۲ոQh<����4c�����ҏ���59��e[������4�ٌ-�h��ؤcP<��-z�t���2�L3]9�o"H%�2̤�t��[�'�av��e�c+�������"�;�Tvʤ�Q�f{J�7'"L�1êټ�	S�r��v鋖.kME�&GD���A��Cw��n&�)����0KNj�y��@Ei0Y�L�ڲl3���l�nY�T�3�C���#����T�܂��e�2��_�a�ϋ"Bf9ӓF(�0���u�&p�`vIKVv��<�b�)t
=�Q�U|���A~�����>�A�t�0u## ROLE & PRIMARY GOAL:
You are a "Robotic Senior Software Engineer AI". Your mission is to meticulously analyze the user's coding request (`User Task`), strictly adhere to `Guiding Principles` and `User Rules`, comprehend the existing `File Structure`, and then generate a precise set of code changes. Your *sole and exclusive output* must be a single `git diff` formatted text. Zero tolerance for any deviation from the specified output format.

---

## INPUT SECTIONS OVERVIEW:
1.  `User Task`: The user's coding problem or feature request.
2.  `Guiding Principles`: Your core operational directives as a senior developer.
3.  `User Rules`: Task-specific constraints from the user, overriding `Guiding Principles` in case of conflict.
4.  `Output Format & Constraints`: Strict rules for your *only* output: the `git diff` text.
5.  `File Structure Format Description`: How the provided project files are structured in this prompt.
6.  `File Structure`: The current state of the project's files.

---

## 1. User Task
Scour through https://pub.dev to check for the latest stable versions of all the dependencies, dev_dependencies, and peer-dependencies used in this project, update all to their latest stable version, update all necessary implementations in accordance to their latest official documentation, handle all necessary changes to ensure proper implementation without errors (including any needed changes in the native API folders such as the ios, android, or any other platform-specific code changes needed). Handle all peer-dependency issues and conflicts, fix all bugs and edge cases.

---

## 2. Guiding Principles (Your Senior Developer Logic)

### A. Analysis & Planning (Internal Thought Process - Do NOT output this part):
1.  **Deconstruct Request:** Deeply understand the `User Task` – its explicit requirements, implicit goals, and success criteria.
2.  **Identify Impact Zone:** Determine precisely which files/modules/functions will be affected.
3.  **Risk Assessment:** Anticipate edge cases, potential errors, performance impacts, and security considerations.
4.  **Assume with Reason:** If ambiguities exist in `User Task`, make well-founded assumptions based on best practices and existing code context. Document these assumptions internally if complex.
5.  **Optimal Solution Path:** Briefly evaluate alternative solutions, selecting the one that best balances simplicity, maintainability, readability, and consistency with existing project patterns.
6.  **Plan Changes:** Before generating diffs, mentally (or internally) outline the specific changes needed for each affected file.

### B. Code Generation & Standards:
*   **Simplicity & Idiomatic Code:** Prioritize the simplest, most direct solution. Write code that is idiomatic for the language and aligns with project conventions (inferred from `File Structure`). Avoid over-engineering.
*   **Respect Existing Architecture:** Strictly follow the established project structure, naming conventions, and coding style.
*   **Type Safety:** Employ type hints/annotations as appropriate for the language.
*   **Modularity:** Design changes to be modular and reusable where sensible.
*   **Documentation:**
    *   Add concise docstrings/comments for new public APIs, complex logic, or non-obvious decisions.
    *   Update existing documentation if changes render it inaccurate.
*   **Logging:** Introduce logging for critical operations or error states if consistent with the project's logging strategy.
*   **No New Dependencies:** Do NOT introduce external libraries/dependencies unless explicitly stated in `User Task` or `User Rules`.
*   **Atomicity of Changes (Hunks):** Each distinct change block (hunk in the diff output) should represent a small, logically coherent modification.
*   **Testability:** Design changes to be testable. If a testing framework is evident in `File Structure` or mentioned in `User Rules`, ensure new code is compatible.

---

## 3. User Rules
no additional rules
*(These are user-provided, project-specific rules or task constraints. They take precedence over `Guiding Principles`.)*

---

## 4. Output Format & Constraints (MANDATORY & STRICT)

Your **ONLY** output will be a single, valid `git diff` formatted text, specifically in the **unified diff format**. No other text, explanations, or apologies are permitted.

### Git Diff Format Structure:
*   If no changes are required, output an empty string.
*   For each modified, newly created, or deleted file, include a diff block. Multiple file diffs are concatenated directly.

### File Diff Block Structure:
A typical diff block for a modified file looks like this:
```diff
diff --git a/relative/path/to/file.ext b/relative/path/to/file.ext
index <hash_old>..<hash_new> <mode>
--- a/relative/path/to/file.ext
+++ b/relative/path/to/file.ext
@@ -START_OLD,LINES_OLD +START_NEW,LINES_NEW @@
 context line (unchanged)
-old line to be removed
+new line to be added
 another context line (unchanged)
```

*   **`diff --git a/path b/path` line:**
    *   Indicates the start of a diff for a specific file.
    *   `a/path/to/file.ext` is the path in the "original" version.
    *   `b/path/to/file.ext` is the path in the "new" version. Paths are project-root-relative, using forward slashes (`/`).
*   **`index <hash_old>..<hash_new> <mode>` line (Optional Detail):**
    *   This line provides metadata about the change. While standard in `git diff`, if generating precise hashes and modes is overly complex for your internal model, you may omit this line or use placeholder values (e.g., `index 0000000..0000000 100644`). The `---`, `+++`, and `@@` lines are the most critical for applying the patch.
*   **`--- a/path/to/file.ext` line:**
    *   Specifies the original file. For **newly created files**, this should be `--- /dev/null`.
*   **`+++ b/path/to/file.ext` line:**
    *   Specifies the new file. For **deleted files**, this should be `+++ /dev/null`. For **newly created files**, this is `+++ b/path/to/new_file.ext`.
*   **Hunk Header (`@@ -START_OLD,LINES_OLD +START_NEW,LINES_NEW @@`):**
    *   `START_OLD,LINES_OLD`: 1-based start line and number of lines from the original file affected by this hunk.
        *   For **newly created files**, this is `0,0`.
        *   For hunks that **only add lines** (no deletions from original), `LINES_OLD` is `0`. (e.g., `@@ -50,0 +51,5 @@` means 5 lines added after original line 50).
    *   `START_NEW,LINES_NEW`: 1-based start line and number of lines in the new file version affected by this hunk.
        *   For **deleted files** (where the entire file is deleted), this is `0,0` for the `+++ /dev/null` part.
        *   For hunks that **only delete lines** (no additions), `LINES_NEW` is `0`. (e.g., `@@ -25,3 +25,0 @@` means 3 lines deleted starting from original line 25).
*   **Hunk Content:**
    *   Lines prefixed with a space (` `) are context lines (unchanged).
    *   Lines prefixed with a minus (`-`) are lines removed from the original file.
    *   Lines prefixed with a plus (`+`) are lines added to the new file.
    *   Include at least 3 lines of unchanged context around changes, where available. If changes are at the very beginning or end of a file, or if hunks are very close, fewer context lines are acceptable as per standard unified diff practice.

### Specific Cases:
*   **Newly Created Files:**
    ```diff
    diff --git a/relative/path/to/new_file.ext b/relative/path/to/new_file.ext
    new file mode 100644
    index 0000000..<hash_new_placeholder>
    --- /dev/null
    +++ b/relative/path/to/new_file.ext
    @@ -0,0 +1,LINES_IN_NEW_FILE @@
    +line 1 of new file
    +line 2 of new file
    ...
    ```
    *(The `new file mode` and `index` lines should be included. Use `100644` for regular files. For the hash in the `index` line, a placeholder like `abcdef0` is acceptable if the actual hash cannot be computed.)*

*   **Deleted Files:**
    ```diff
    diff --git a/relative/path/to/deleted_file.ext b/relative/path/to/deleted_file.ext
    deleted file mode <mode_old_placeholder>
    index <hash_old_placeholder>..0000000
    --- a/relative/path/to/deleted_file.ext
    +++ /dev/null
    @@ -1,LINES_IN_OLD_FILE +0,0 @@
    -line 1 of old file
    -line 2 of old file
    ...
    ```
    *(The `deleted file mode` and `index` lines should be included. Use a placeholder like `100644` for mode and `abcdef0` for hash if actual values are unknown.)*

*   **Untouched Files:** Do NOT include any diff output for files that have no changes.

### General Constraints on Generated Code:
*   **Minimal & Precise Changes:** Generate the smallest, most targeted diff that correctly implements the `User Task` per all rules.
*   **Preserve Integrity:** Do not break existing functionality unless the `User Task` explicitly requires it. The codebase should remain buildable/runnable.
*   **Leverage Existing Code:** Prefer modifying existing files over creating new ones, unless a new file is architecturally justified or required by `User Task` or `User Rules`.

---

## 5. File Structure Format Description
The `File Structure` (provided in the next section) is formatted as follows:
1.  An initial project directory tree structure (e.g., generated by `tree` or similar).
2.  Followed by the content of each file, using an XML-like structure:
    <file path="RELATIVE/PATH/TO/FILE">
    (File content here)
    </file>
    The `path` attribute contains the project-root-relative path, using forward slashes (`/`).
    File content is the raw text of the file. Each file block is separated by a newline.

---

## 6. File Structure
�>J���*И�iXu��#av���jc<H-��h�F!�8��
F2�0�z�f�LrV`<R�l�zN}ݙ(�F�]9��*Y�ˑ=��]FhgR|��t��'�q�D/��z�J����x*2lx�3z���e��āT��ᐫ�WO$�!f����7b�0�g�)h5;c��J�]0���I�'
����Ċ�D�L&Ȉ��Զ�d#�w�a�7z�I�I+OYY ڬ��Qݲ�	�R�t�#=�uC".�vuʌ8���є�d������[����)��T7��;%�$���(��w>��ѨC��XO��z�Ln5cئ����l�'[tYY6"��ӄ��0QI��������b	О��5��B(?�҄�s\c)��F�J�e}a�Kk��eD���8"�s�����6dcP��)Y�8�rc,5�I\�2���GsOCtb(�ݗ����K]s��T�`-��&OD,�m��[�`S�����Ɯ��zH�P�EC�
+��2��u���8B���ʱʭ�2����IsK���U5Zۆ/�������ռ��C\�	�cvwn�dH-�����N�����j������J��wU:L�*��~�� =��u����kUz�U�F���TpʢϪ��nP�z��»U��oT�&ޣ����f�o�[U�<ߊ���J��I�}A�/���E���ֵuU�S���{�6vpzA��U�����W�N���]�.�e��w��5�����~���oHs������ ���⍩�eF�l�f��|['-�V���$�kḑ�bqKCAj�fL�c}���~�V�5�7_͊kH��ы}:ҧ-����F�1�}���V��|@������L;N(D�w,�Z��I����m��!�� G�G`��+-3d�]-ޡ��С�A��Kb%�6��uNT�Ӻ�]��n1�ңݲgk=��U]�K�DT��t���w-��k݉8Z},�'��U��V��ښԭ���q�#�%���h������˵�D<�隕H%�^N
U-�i�Ŧ���V+I��2�Q��P�����C����v�huv�6j@�G���|�W�	����~��C��.���$}�<@?S�?����6�ћ��@�K���l�'�q��.S~燺�q�!��K���,�*GU>��)�]��'`,ki�Z�aa�rN��紎x�V��{^�T�>?���Q�S�e�v�T~�_�^���a�z*��{PS�?$�^�L^����6��k6���x*�C=y�[o�zRp�ɹXbNGę6{��%ј��_:b67!(�du���?�����?柨�S~c=N%������?G�Z���!�B��U����W�N�ң�4�
�o;3G:c���mznJ�?�+nyO�?�G
�V����*��w"��x[@��鑐Gͷh���gm���岔��MM���W(q�NRi��;��#&��������i�t�X�D>эB����CV��b4�y�˅ݟ��n�SVν$����L����F6��P
?f��C8��a���
g|�]��P��/��]��e'��Ⓘ���&��.k�F���nI����pZoi�\�L���{�qV|��}IQi�+�|8�tZ]v/��0g�֏���b�<��n��V<#Ǆ�>�2���nGD_����s�(��G��U�"7���2i^	z��Q�Ⱥ�g\��A%�NN��aډ���d<������d*�у-"�
`�:�Nq��9��+"WKF�i{��8���W W3h���lԝ�83ِ)� ��3�8��XL�N�z,C)q��V�r	`�K�>a[2אCw�Gu�Ƥ��^�dsJ��ܱOxr�#w\`e�bN�d��W������Ք���M���%��렕cB��K%ɗ�;�ҥ*ә�d��9$�[.�6�f���3�����$NC<r���PPA��;NNM��X�q��-Q�4��oe�\R]zr]<a4F�(23 3.f�Z�ϑf6f�ѹ��Ew:���!7����ɵ�����e�-��#q1j~2�A�S�Q>�J�����W}�
�x��B9k�O���'��xN���&�{�u(x��W>@��h+7�^ �0�/�8LJy�a��W�	�l�C{����J����aͦ��I7c�ܡI�ЭD������H�g��\�~�����H�sUE?M\�fRK��4/�k�Ao?���cJ��ヾA��V��++�����=Gŏ���:��r%d��T
��r�u�e�\E_�/A�|:��L{A%@K�6�׃��t;�	�f@�}tfͥ�t7�|�5��b�ױ�UGnB_ #7N?B�u�tZ[� �h�,�N�D��k���y�ifU0��f.�y�9���`��J�:p���~�]-��
�V�x����A���h�d:��_s�N�-��\��4.�LZ�Ogl
*�5o�n?8�zP駒!����h�B��h"�����4�.�������"�����d�e�A&u�h��w-Elv@�˰�^��.���
����t֬7��0"��!�D�Eߤ0k!%�!���F��Eì����x�Ṋ�w��\?B���Fj-��k~K�St&�7�i~[a��Y*k�T�!���F+�J����F{���<Jȓ�+�& s�M>���zs�ޒմ��o��"yX�������e�{�DO�N��?�ӳ��pm�z���+���[%�_p- n-\�آW��yѱ��-������*������R�Z6�sm�\
���88b*"1�X<���l �o�c��>� ~>f�����V�1�f#=e�y���oO�q��� �(�<%��i9�|����h1�-UG�y=���
��VU������ӊ}�V}O��<ں;H~�r�7�m��A����;������*��΅X� /�ﷃt*U��G�~:p~�F���9���pY��~���x//�=��I�|�6���{�^p�)�.���:gy
Rq[E��:w��ym�g���S��+���࿆�jt���7�����$8e���XM��ӧ��x�Ss?�;@k��m�ַ��
t� m�Lъ�j1���Z��TT
�8P�[�i�V�I��i�mx��^�ؕ�2�1��^��Cн��r�<�@q�v	�����݇M�~{�bD}-�~5ִ#�{��!�oAL�G�<�(x�~�����a�
�� �w ���K|�
p� �O/���a�y�^�=4�^����W�5x��v�����d���G�;l��؆��Y�������S�E�yÆv�\f�7�m�؟Vx���q������0�O��(�{�s0z�6����1��MjD�i�4o�&�[vX�Ʌy�σ�R����j<�u�U�*�	�^nër�"�k����`[����X�ԅCB8��"�X��`x&{)�5$��i����]	f�M�����=Xs&�
��g͵�~m�+On�ӻ�E�$A�w�:d��t��%�ٔ�l��r�!v:p���bѤR>�Х�b@��Ļۆ�
��@�JW2� K��:@�ূʱ�o����o���A�t9`A�Y�3�\x�7�J7��"����4	z��<�X	-ϡ��1��߃�(N� J@n�#h}�:�A�#T�&�y�jߣ�¶;����%����q/}��8����n��A����!{&��a�O����[�')��EM�RQ�Z�m��n�PYeW=��`�E�FqA�oP��(e���� �e� �YK��[�(����ͱwl`RL�d�Ӭ����Ϡ��؈9<��
lh��R�g�a3Ԝ)�/i�
�k�&
D��4����_���!R���ʗ��NZu&8���?���žS[�7�Y\�N>��p_��/����
t���Ǎ�����/�_��`I5~�����+%�h�g)�A������������ws�9����<w6�λr�	�0s��1؝�	�Ay,<�؋:@�n��E�p�����PK
   ��G��L��  �	  -   org/gradle/wrapper/BootstrapMainStarter.class�VY[�V=²��l1p��NSJ�4	���`�:���/FD��,��.i��ڗ�6/��|M��/���v�d�7��w��;s�̽c���o/��e�qWF/f$�/aVF+�d�ü�,�aI�1�X���z<����X��0$�k�el`S��Hp7���Ň���P�G�X�'�P�r[F�R|S����<��� q�L1�1�`+��6���m�$���T�
���� �]-+ 3�t4m�)�E�Z��>��wM��ڴYV5#n��ͬI�,_
�m���5��F:�-�HO�͌�i:�t��V�"��0 �!��a�\~X@��]T-B�4
��3����L5�HpO̎���JT�p�P;"�%������5)��%��YҎ�:�l/O��~1y��n�������$۷5��J���ьTL��]f9dzBy�d.�hNuT��'[Uz���i���f�J�9�T�V�"܃� �)x�t\�:J�F��vhؓ�D����A��|
KAt'z�EܪE���=gϱ(���������)x�CM����{T		G
>��Ed�4Ѕ׵m��_�+��5�rÏÏF�yK�7
��wT�� �9��2��`fg̜�
�ԩ�6ڻ,�R}��5�(Ni�mm;�=����=��������荜+A����U*tS(\z�&�+�P��UY�Ȳ�"��Z���?��,S���*O8���T�T�p�#]p�7�����ZL�gw)����⾵,�+���̞���;C�נӺ��B�ͧ�`�Z�%ܮqf��L�U���E:jښq`>�;|�4�{���D�j����6R�����\�a(\�K$"���\���&�T)�3զ�@�j���1-2� X#�5�E��|������@�-��봋�,��:���u����;�O��` ��L�!����&;��͡�cԭ���������?��G�1&��1���_��҄7��_&�Ƅ�M�<�'|�@�3L���Z��	�p����0�b����C ��yJ&_�1�@�$����C�Ҋ�C���4��}�x�Wn�M�+�vuo�n�$"I�&/�OP��1E�Ѽt��İ���yt,������mb���ior�y\����	�������˿��+��Z$|��c��qV�v

��(�A�q�=�:Ma�w��xq��1�):�L��%��>���=Z��YJ��D���v���PK
   ��GHַ$�
  #  (   org/gradle/wrapper/WrapperExecutor.class�Wy`U�M��n6ӄn��)),%m��E�ݖ#IS��lҚ����4Y�����
((*�"DE��A�������>��o���L6��m
��������}�{��s?
`���-~��[�(��~�D��nY�Ǐ�p�o�xg�/�J��]AT�`9�-�{d8$�{���dx��| ���C��a�}$���/�G��|���2�0+�ex(���;'���x$�GŊǄ�A��O�����S2�t ���������d�Y?>��8����| _��|)�/�� �*�O��� �������OU����H���	��%�������� ~���4��)�������=:ܷ{hxp�gxdLA��2m�֑�RQ�L�&6)X�m�Җ��vhɌ�`e����h�GB��P��6�押������<kwt�_��]�C��#��=�=;��Ӧ1��VBO+X昞�Ɏ��:ͯZ�ښH��*�3at-���}�X�+'�F�szZOŵq��<f��{FILE_STRUCTURE}��3̉�	S�'��}�F&�c����y2�f%���1Lt���$L��y@����VWy<==��X�¨��m��Xu_"�d��usD�K<���ܡ�	��E�͵�!�u�����7'R	�|����cx�C�Bau����1}�E�gM&���9�l�ԧ5SߒH3;�3"d�L(8�y�� �[�ý���1c�^�	�H��s��Ե�-�5���<z�N�1���;5���Mi���9�l$��/SAE|AXU���C�DmZ��[ �b��TN�������&?~��O������,T���BR֯ې�K�/=�%��+-"�,��e�Ӧ�5�H3%qЧ�D����pD*h8�B��E��F��pضS������`��6mܺb��n�90���s��[����G�$������������_�a׉�
w)(�눬c�՟H�ɷ�l0j׌S����.�����J�)�����!�\�}�C���/��XM�ޯdH��5~�:]px8��Sz����6����ИpӚtS8n�LVX���ig�,�����oU<�߉�߳jY=*v�e*ưKťx�����*�J)*��?RY�S�]ZZW�'<��u�IşE]ǐfM���p�U6뼛�I��h43��o���U<+lꕉ�(��;�.����p�S6�%5Ez4��62ɸ������"��Ɣ
l���o����_<��y��`���������|q[����./���L"���g��0+YU����Q �S�J=�.��ʹ��2�jwRc�I�`_;�Q�R<3F~oW��qӊ���%��.����*r5�F7���
�-\s-m
k�x�΂�M���I=���%����6�)c/�
#�J�{8�vM�n�k�}
S�i��W��r�
�˽���bNů*�¯U�R
�0���*K�*�R�*'(Kta���k2|�~�Q���cbn�͜y�X�'z� z�P���aM����z�w�|��ES�,c�]�i�ܕW�+9�˽x���RN�(޾���T_�.���6�?M����:��eCl.��&]��P���Ѽ����\;�V��[A�ڕ�v��^��q��;9�562i�rO�
���&u��%r�ї���$��[x���أN��x�a,��ʸj��TAۢns����i������Qo��b�c�y�vOjf�����yf��YZ���KJ:3C�\��h�U�
K���~a1�12[��ۮ��>$�
S�K�؋�v�-;��Eߟ�����D=�c�T�T	֓��+H�z��;<�ɤwz�SI��C�EHnv�Cr��_�����>�U���q���@9O+砌͡d,*��oec�(o��Ɩ�X�3@3t�˜S�c됙haU`"';����"pV̡b,�=�ʱ�H�A��X����UW�R���F:<j�\�d樅����v�(��ldU]M�'��H�a�"-�Qi;��u'nki}u��=�eY,����ɢ~+�8�Ykp�V�k'9k';kabvJ���S��F�q����Yk�ך�a�φ#���"p��f����tc
��=X��K9n����8�8�Q?'�,����%���D86��y��SF'�\�{q!���<��܋���SABS6��6��=�*�go)�|r���yN�	XkQl{�v�<��,ڹ���ⴙ��`�lm[��"��t5.��ѵ���=�+h�I��o��VN�v{
�C��Yl8��s8�Y�~g���ZuVa
Q�v�舭RuD�*��29E�2���
J��f�	�K
Ylm�	mv��l��<����C��E����-eN����/�>�E�NR��Yl}�`�!T���l�)���S����fr)���~	������,o�c�彗^\Mo����g���>��euH��\J�j췛�A�G���Jb!�[5��1|q�����Cȷ�y[B��PG�:Ƶ.��S�A�VQa��T�o��6ԫrE���?�%�x9��]w̺�?������_	e�����N���;ສЙk����29_x�*�����kq��I�FK�	�:�6��Z�����d�5��Z��u��r�/�^E�{v=#Rb�^���l�f�HP|9 $��7�:��-�@�Ea�8�Ї�=%�i�
v�E�M����T�Hj-&�FO���$�7rVb˼ٕy�2�Q�X�6l�������7�ze�3��DE���mFYз��@���XMY!����6&��ܿÃccΖƜ-��-2{O�+)a�F��zC���`7��2�����t���� ڐ����Vh����?PK
   ��G����
  B  *   org/gradle/wrapper/GradleWrapperMain.class�Xx��N��jU8	�ÔԐ΀���Y ��f�m�����V��Q��b�؉�H��8=N��8'يM�'Nwz����;�gwu:�-B_����y��y�
O>��� ֋�*��.�2��\.�P�J\�W)8�ǫ�x�ܽV��p����zoPQ&���Fyx��7��
ޢ���U��M�|�\�!�w���R�n���~	z���x����X��X��
PQ#�܇*����ŇU�1!�&���\�cʏ�����T�9�c*��T|�P�I<��S
>�b3�T�|֏�I����_��_��O��/KFO�����~|M�ץ-w��~�.��ǷT|�Q�]?�'������\~��������g
~�����y�����poǁûz�;������+�:����1=>�������63����5��R�@(���������=ç�E����ї�.��#*��G�m��|m�0��uE�����#ѧ�RI3���DT�]���&�v����HB���	}|�H�w��Ag׭G�4�7�_�%u��M�������8P]λ��@��5�;�1����	��V�HJ���0M�=J��q=�4��:[�H,n3�����4v��CFE3~��$�e�����1�/#*�A�4y(��e�<D�r����^��aK9���h,<�v:����.s�v�̑��E^Q��IW�B:N��e��p6�t{ǩ�1mN�><����u��1{'�:b�W�����r��M����<4A^�`ܰ��:{O�-�TFa��cL[��IU��3`$�6F崌��x�Ap�UII3���I������n������I�IEc����2h6�l-7�]8M���>/��rB���p�˒��,j��j�-n�M��&V�]$
���tU�;5��;�M)b6�2)4�
�V�
����s\��x*j�g��+������?�O
���/�+�j�B�ғ����������jH�
��l
�T�/
���z��S��X�����<���0k�srR��q���3��6M�O�F[�Ic�5!��˅.@��U�iI��^K��B,`�e��u(�gʢd�P>M�BM(���S5Q��XF�a�5��$BŒNc��O�WD�&JE�"�5�HQ��J�XK4Q%���9�+�=�/Rq+:fd�AE,��ER_�Q���eb���6=7�аAw�1B���L�ӳ��f"��b�M�c���=i�j�$k�g�iϑcF���
�RJib��7�)�\�Y>Z-.fh��!%䢄��UHŇ)�ҫ�k�F���ZQ+��<�Z���&�7"ѣQc8����"�4Q�kY�=�#�f�H7�C�>q�5KcѨ�u���Q�mX��6E����:���DRo��!3O��o���L7�����4�B���r��`=�ut����2/Փ���Ԙ�.�Z���	�FGR	^�5ye7���
��c��x�Ydv�c+1���X=ݱ�ŝc��G����ʸ�>g/����*�^�.�Ʈ��(Aeys;�S�v{N���ď�yӻ��O,����z���E�)�]7'c�V1�<OgU~[�b�V�!����S�Ӎ<ϼ��-����;�
,s��.&PLE��O��yeg���mt2�(��-԰��rd��xL��J"Ƀ�u�
��0W;?R�_6m��ı��Yl�����Y=}��]"j���P승1?ȷ�3��ٖ�u�����w�
GN]��737;-�k�����Y�,��8SN�����M�'�{80�I���Ȩa�H��)=F-�&w����D`!��+<�qȃ�+��=h�;d'�5���@ި^�Ew�����r�u��RZ�LA>͇��y�V�#���e4̝������j;�Þ7��<�Ƙl�T�B�3r��0� ������pw�z�v��
�qơ�`� Va��!�rD���(�~(k�r0k_����}))�|�p���0��
4L@<`��ZhW�z��� G�[���Ebq���	{|�'����o�
�&P����P���G�Z(����4���f�%\*��S(#eyKA�@�.
���N��E	*�N��(��X�FU�'�-���iA�Y�R�.X4��ZԠ�0�eC��),�X1��Ma�P�?���x
5��&��3��F��:z-m����Q�,G#.�:�B3��mWc=�����	�b3nG^��8�+�Zm��;"�Q;|��99J��!D�%C� ��XH9��Ĉ��1�8�~=�n�K!9\Kh
e�q(���6�і"�3��N2"�l9�����
t��㝛E�B�PC�n�����\Pch�!�ThG:���QKV��J��:f���+�27��ɮ��w6'�:���������V��V8�u�k
4�i\2���캳�Ue������z����46�qi���i�}(������	\q�2Z[��z�%0p��lZ��;��x�9W��	\u֖UN�\�j��2�^��C%��:�L��yf�Wɐ݊琾�I�\<�p�|-��>��w����h���Jl}:%v0uwv5R�]���4vw����#1���QW��y����s{쳽��=��}%�����m�/���������c���*��%,UL��L��s#}���`Է0mw3a�0Y��
��HKo���J��x!�+	}�X��x��$�p��g�.��(Jc�ݨt����i�>�D�L����(!��L��Xa_��.��.��)��i	%L�?7�n��R�r�j���PK
   ��G��x�  �  "   org/gradle/wrapper/Install$1.class�Wkw���Y�<IQ�0 +�J	$NB;�A1!N��M[�� �3�QG#'����#���whKZ�8�
�v���~�G~�e�d˲�&�Z>��3瞳�9�>���|r�F�}�qX�wD|W��D< �cdc�"�Cc2xPz�(ƥ�D3!��[�d�p�peP�����ĺ���h��p����j���6�?�p����~��bx\����(���)�����&F��F�\'#x^�}ʰ;m7kأV�N�6Vd&�)#m�i�)��M�*���pYm�Yc%�r��=7�1���}���J̲����/y��)te\/��{F�6�G=�P0��H��כr~�1���ѻi�piR!�
'������V�*vnP�he�)��m��6˱�
�'�2\��f��Z/zQ�QwT�����2c9�Pir���cR�xF�r��,W���R
���0�m�&5�,��΍\�Fg,T{����1�0W�PN��bu*\2�vz�7�
��I�;bz��r�4s���c�FNA���2��i�/D�^�&Â
�u}��Wx�P[庆)�B�!(v˚�k1����J~��0�iٰ�w������uq��l��L�O�3S�Y�a��e�`�z5�=b��v��[da�����s��:~�ul�V�QG��[p��Mج�6ܮ�/�W}(/c����U������9^����z��GtL˸��xo�xS�[�{�(�{
�������_3Z�bt�N<�&�wu��S:���V���{�:���|��O�s��?e��a0`ڦ/NH&������FpV�'8�_t�癹�CV� �$�:ߥ���L���B�����,�c)�M�5TX�d	r~�i��p��z������E�-�ho���Ǘ�M�!��D82&�F��f���~�N695����Ĩ�*����N�����*�JW��
�j�+�s�XѵK����Ǚ#�5�<g7���\�	�d��j�	��� %H��ׂ4�R2n>_�Ƃ�U��R�K\�Sc�L���qpr�@�v��k��;`������k�
Wт�
�(IG��wy#7�A*]�}����0y���b��������>���2�/�r{��!�]hN����U`v_
L����ܼ���u����F��C�$���h�̹�
Z�Y���o�zW�]r���!0��>C�hmo��
`�[my�UZ^r���\��E��t��}�����ۋD�ޘZ?��y-���R	mZ*�A8�h�Ak�"Ѳ;([9�|�^�߈o��8�MB+zo��%�[�=��-e,;�ء3hFz��+(�Xy��jCe��ƚ�eħ��
%���e���t03�"�)h��ӘX��s����X=">gqe�0~?S����UW߿!�����;p�f��f���Z��ZB;^���.�k>Ctk�!�u��s�j�Dx׏����U�4�,�����YVe����-��x2��VfW]������Z%�6�s�~$����B�:�Ք�H�C[Ȣ�s��Ȝd�I��CV�<�����7v�+��8T�kq���j���Ȩ�أ^��z�<��㯠��I��8s=�b/�/ə�1M�R�QRM�~U8�v�^bia�a���U��_5z|��~�Q�Q3ܾ�~�\�^Q��Se�|z	"�� ��P���H��U����L �s�;���PK
   ��Gj j��  V  8   org/gradle/wrapper/PathAssembler$LocalDistribution.class�R[KA��f��t�q���[�y�k���[KA!��|(L�!N��
�M��J�P��?�U<3�Ҩ|ؙs�.s�˿ �ū9�`�OM���}��Q&�m5ȏT�0�芟"VY���~ܪ+M��c5��6���-ډ�i��K���` {�Dj�yT�輓D���I��l���ֲ�$,4T*?
{M��	�F�ɡ�����k�m����js���� M��%��ydw���-~[ؑ��%��Ru����<�[5'_��n��$��6� E<`=�f����Axw���ZIDډ?7���
U���46�����?S@h<pC@�d,�o����;�|/G[#P�N�z�B�f���,��Q��e�,!A�+efc,�����c#�n�ae��M|�D�(��#�lQ[��z`LHx|'ؽ	>����=W�-��ĝ��PK
   ��G�cJ  K  !   org/gradle/wrapper/Download.class�W�g~f��3;9X(��
l�"
���$�v�4�,	]Pq�;�,��lgf	Tm���n�U�^[���l(��UT<Z�V[�������V�/������{��{�˕��v�Sec��9��/[e�(pU�qLƢ
�U��*>���#bσ*�Ge|L��U4�*��I�Sb��
Q�|V4�S�y|A��%�<���|Y�W㠲F*�����z ��D��o�x�T�-�T�SL�-���S
�T��U�=�W�O���������e<#�� A���a�fu�1	��k,췭�a��������;o�n&���-�q���S���#�wNO�-A�IX=j�����
"J	�F����M����h[��`�J�
Z�8n�遴���������q`�[���yV8�X愾`H�Տ�Y�L�];c��-\�mưvtqk�̸{$�D���Zs�J�f$�G��7�gLc"�0k��٬��Գ3�������g��M���ͬ���
I˜ˤ���2��	�³�*)H�-g���T�j��d
/LYO�lá��ڭD!e8n�,�n�Օ��;�4rbё�	��nc�kئ�e<y;���85��a�����l~nN��;<��!�o��VZD7j��QR�.Z�긓6r<�y7�w�zC_�u?s�x��x%�L#)��_���e��Y�&8�|Vw���(%��vգ�J���Qņ�L	��*NJK-��
�,�HА�T���<�ŭ��4D�yG��/�h؋q
��=�1�aT4wc���w�\NPC/��~�g%���n��wW�|N�
��/j8���%��0�	/i8���rNG<@�T56Ĥ�B�K��EѼ,�
��s
)���8G����_h�%~���xU�e
��oe�N��^�D0�������ϹFJƟ4���%�)�=Y&���r�u��J�_DC5�_Ɵ5�o������M
���!�����V���l*N*�r�Mt���������tڣ�ΐ��U5�
�Ppr�(�,�-
o�*�0a���,o�;�/���[���h�b:҆[��		[�u*c�k�Q��V"�
U[�:�������5��|�ucU�����Ъ��{%��m�)Λ�oy���Kel�+���
�J���k��T)Vj\K��Z�֯Y��J���L�Y)Z6�pQS��꯰�17S��y�
.���R���UUG	��2T[6����^D�t�j(s`���:�����J[������\��꽟��	�JL�91�V=F
`����S�.o�V����H��4jH=��uF���g�θ��̴�b"��n(Of-Ǩ�Y^��bβtaw�#_Y��3����v��މ 6`79��Ї[9��j�a�}�8,>1^?���ً}l�8s���~}���u��ECw�Y�)
�����v�l7q�f���h�F���+�E;��.ﭺ��/y#���8�Ǯ��b��p���h� %!-!tj�{#w�|ro�ר�Q+��<J��-lNs����[�����=�KX��\��r���k/��ЄI��\4὘�f�c?%8�^v&��)]͕�҄4�)�q0xM�hN�k���`kEF���5"���n0в���2��v��@D)`}bg��{FILE_STRUCTURE}�!R��'��<6�o(`�lN��,��R��zXߥ��kq�B��Sh�~��M�|��Ծ���*�����V��-�%( �w�}t��M�Z�uY�G�Kgx� N�� �]��b" �L>23�wavb#v��&J��2���p��Äގy����
�� ��S�(a8If<En<C4�% /P�<e�Rv���({���	� -G���fp������ҏL/履Cx��3����>�UV��e���`��->C��x	d�~��M�"ɾ�|��X,H��יH�x��T�'��v"��v��] �/�`x@tr��f�elO4P�!�����H �F�����	�7�h��!m�����ʩ������LA-<�zu�����!D�������S�O=u���la潋)�o�#?���(��5D�F��>���^�8LB-J���� ���A���PK
   ��G�>�P   N   #   gradle-wrapper-classpath.propertiesS��O)�IUHIM���,����R���SpIMV02T02�24�21Qpv
Q0204�*(��JM.)�M/JL�I�M���**�+��M�� PK
   
��G$ٖe�        build-receipt.properties5��n�0�w�JA�3h�V��ҢkAK����I6������}���au���~��C����\�/ZIlP)T�����^`����[��v�Ʋ�*96Qo��;���h��gT5�p����-o(�Dvf�.Z�-=Ҝ���7��b�(:!������Å�w�~%��Ԫ�ā�3��xgS�#�y~k��@1�����B��(_��8�PK
   
��G              org/gradle/cli/ PK
   
��G����<  S  1   org/gradle/cli/AbstractCommandLineConverter.class�T]oA=�+��m�����t�>�BH�hB����e�m`�C��ߢ/4����2�Y�P���a��9{��s���?�p�#	���5��ϕ�By9yE�x��\Ye����Z��p�Ն���a��E�jw	I7|���Z�U�	������c:��t�iw]��Haٲ��z��Qdu�;�BrQfظ�������&D�V�\%6�#ǹƙun�]�s̷�X�3ػ��e9�H�%Ҳ����I�k.�=,ȍ�-1��ak^����1�蔕�)��9��(id0^}�y_��7б�p��:�%����`i6��t*�㰊j�BL�F�
��vU�W�m_�I��m��uוD	�I�8H��7��Լ&��%D���DՄ3�qON�B
���:ACvs�π��b��0�\8��y� �3CEw�&T*���kY�+$@B��!K͡5I�YF6V� �ANwh�`+�&�XE���KH�,�έQ���FV#������J#h�Rq������
+dAy	��~#�T�N*)o�ް��OO��Sx����Α	86'��?��?��'�k�<ų@z�����V`��PK
   
��G2_e��   �   (   org/gradle/cli/CommandLineParser$1.class��A
�0E�h�Zv庈kC�PE��v�-iI�p.<��S\�p>�?f���x��CD��ln��m����M�J]�k�'i�u#��0��BW����Ք!f������,�B���y�@�wZ�͕t�!��BI]�����#��HI�9|g���|{����
�-�| PK
   
��GRB	�  �  <   org/gradle/cli/CommandLineParser$MissingOptionArgState.class��]O�`���6���d� �2�� c���&�/1N4�`�W��5�S��/���� ^H�#����2��-sKƖ&�9O���״���
��1�U�Q��4%ի*��gE)�D1�5��u
KD1�0�ؓ�p���
��:��@�^3����\ٲ�Ū�+�^�1���U�qQ)B��&�@�)ʊ!��B�@n�!�fU(�Ai��W����|ۤ�Ჵ��MnR#���yd8�!�>��]
�Z��EGO*%U�o�u�l�GQ����/�\T��Mn˹-�0��2�#/��hAlu@t�����%�-q*�2��N�w� e�&���B�gs�Q\g �����ݧ�-F�͌��&�� nX{��~ϐ�2z�fZ�C� R�6�A�	
Ӱ��ἆ%�GA�����a��3���@�L�v0Lc8~�c�s�C�7�]S�af�=/���b���!��i���!WiXN�$!9!:"�>i!z&�� �|�'�����d&�w`8Ig��9N�O�XF�i�Х ��uD�5x
�Rl�(
��38�qhK�C�O ���p��:��m�O.�6p� '%���0�o	�%�r��C?7�o@��Ӹ�<=@Q����O���b쫇�|C�Ma"��!˰߰���d^7U�ܰ�^��t�^EY�3G5Dh8%h�+a�V�E�3�D*�PK
   
��G��M2�  �  =   org/gradle/cli/CommandLineParser$OptionStringComparator.class�T�OA�f�eam���`��Z�Pd) HJ���	��ަ��Yv������ɛ{��&Ƴ���v�Bkj8tޛ��y��o����/� ����
jѱ�cр�%��:�U�lQǊ�U��-��`�!��?`��n�b�H�z�<�Z�Q�)2Zqk�>�T�(���gH���u�O:��{|"<�Cb�q,�l߷�Yq����Dݶ̚-M������oy�ޕJz-�Qӹ|�H�
�N�l#Kݑ�]:��Պ�}��^!�C����ެ\k�W=�jA�;����W{D9�!������Œ���� ��e�AS���/��@��%���ئRƾ��j��n5��nIJ �dO��c��y_�e�[��o9����=�}���&�fH]�Ͱq�i�R�-�x/���	��r�a��hf���2=B�F�=Y
\	K�aڙdiZ_��L��*�SH��hp�PsE�D�?!Fq`�p�����Zl{��ݺ�=�1��#�[�s��!���B{�./z��i|��C�G1�ǐ������I�Vy&$��nR�$�B�;�C'r���.��"�{�i�"�����L�lr���"�.0����3m`�Y�(N�͒��V055Q�M�<����3�t
Ʃ`��1<���mcd3B��= �� �`O��PK
   
��G�#
�G  K  1   org/gradle/cli/CommandLineArgumentException.class���J1�O�3���Zm+����Uו���0����t���D23�k�*��|(1IK-�Y������������
�2a�E�E��9炧=���O�Qz�Tqv���K9fU�v��#�ti��ˀFC����b!����ė*�BE����{�2��ȅ
�����9`)��K��,Ihh��\��x��&J>���p1�Y�I�����T�������ة�Q�cB����W�~Ϳ�D�c��Dy�f�@�]��t�ӻ��LA^����%�6u�V��1�����8(c]3�2g��y��������=ݴoa��̝�̩�
kq�v�>PK
   
��G?h��  �  =   org/gradle/cli/CommandLineParser$KnownOptionParserState.class�Xkx�~�d�Y&�� �V���ds�д!���@L���awL73ۙ���J[��^�6V{��z�JB��^���֢O��y�<}�ߟ�=gfv��ݰ�?������ܾs������@;ޕ�w��%!�cL!X�I��y^l^^\�OIcB����w1��0�X�/��Lt�L����{$|U�z|=�{el�}2��o�ɷd|�a����=��_�����P��8? ���cu���	'yy(����a��gL��
��K�%�yT¯$�P��kX��k戄��ٗ��m�i�vOBs�h�����[�'��X�h����43�g��~�vt�&��@��A��˰�<D]�G`�Tĳ���q5W�p��}!�R��,z6u\K����}��q�5���>�q�lŀ1bjn�&�5�w���f����bq���Դ]���L��0
�K�B}q�eF����;zy�l�Xq��Lҟ;���C	�D�����l��2��Xw�iM��U9��^܀¬�[�2���Ԙn�����հ�T	ky�|q�������G\R-�V�l�Ftw���[^Z�p�w�L&t�J���%F��ڬ��uu[s-ά
���}A*I/��m^��E>�	�@����Y���K��#m������^:y��}`	b(`Vʎ�7����hZ8h
��W�
���'�̇�۶���g���
v�G�VlWpv(؉�)�B��A	4.nS:�v��A����	:W�V���:uj��մ\����U�T��B�q>��:��Ȳ��I6�)O���ݦ��%ݣ6uBsԤm�q=��a�jl�-N+8��*�7+xC
�bH���.�k8e��9t��Tĳ��$��� �*��9	�
�cN�sxF��
^��
~���ڛJ��jF��N趾d�/��������?�B����Q�2���xE�E�*�~!Ů����6������5����6���ד�#���f"z�HxS�[,�m�U���b�������j��%����p���AM�i ��/}����2h4��i-�lӨ�o/�F�[Xu�lxz�
�o,<��#��^����Z<��;��^�=F݉�5�y�豖g7�eÿ���%8#��^�+�XLw����6j�=K֕ 
xr���^�i�����/�����=�T��q�ܼ42�b6�������vc&��>�ܰ��h�0g��|,eŨ���C��gm}C~Rq����ᦊl�>hAd��f�^]�2gb���V���%%��j�O2�!��ǃ��L�&�~s��p�f�c��9Ov�t�YS�r\�w	��7�V*9dpŮ������[�� [��3����Gҩ7�~�v�O�rDx� (�S�������l�}i����z�K��������g:�$�D)a��h�J�b��9��gP6��h��h�����VD�	+��$�D땐h�<Y<�*�6���J�B�x��	�M'�X>�b�Alw����Ķ�<��/#���S%��?:����i�pde�y�.���4pW̠���i�NG��	.o�Ś����3���OAO����Y�A�x���w�u���
��i�mps`�YO�6jIKxU��f}�X'0��sX?���p:W�*��`��P�s�:��<%J:<��h�J���)g�x)�?���%m�x�INe��I�eY�{����V��6�Z7��l��u��υN�o~e:W��,�{�f�V[E�Ղf�k���Qs���bj/����s�<�:J��H�4�,m��&fB��A�-��umY
6����M������$P��u�X#^���H����0!^�q�&śxX����m\���x��R,��9����rv����`n�A���3^���*�Ǒ���t��Y�Q	�G-�M�$b����:T]6qfA������2Y��շ��XPa
�A�)Ѓ
Sp�Wa�`�����0>�%Y9��������!��e}	n�/*�XO߯��i;��B�� PK
   
��Gk��  �  7   org/gradle/cli/CommandLineParser$OptionComparator.class�UmO�P~n��Q:oS�Doe� �b0�!�c�,�Z�vğ���_�Hb���"��6s�J�C{�=�9Ϲ����~�P���4�d��W�%,��(c	�^JX����^IX��u	E	^3tm��m2$r�}�d�}��w���������5�����C��}5\��ޱg�V�nk����R�,�)�����X��N]�;ڡ��5�P	�ЬC��渺3y��� ������ �,�#�e��}��0�Ђ/�]��i'�jjV]�z�aՋ�� S�6����^���~
���u���-	T�t�Y+y�&�d��uK��<���������Y�$j�j7���c���v����
2�W�[�V��A�ۛ+m��S�*(�u��B1lņ,i�^�\�r
�8�;c�� ��=�x\�8n��pﹸv��rCQ���o*]]�� 4���|[Tuޒ��%`an�o�t��.A�Ai�U���
W�G�ŵ�
��S�/c��o�4<}�u!���� �TZi���>�������Jv�!�� �a<�@*Q���Ȓ��̜!q�;G�'�.!��v������{nD<�|ڊ֋1(Н���0�Q�������	I��9�b�|��b�$��m:ݳ�t�B�HO�\�g��3(��W��`iL9��I�����k�
a�Xtβ�E�$�����i���,�:AyLS)����"�"�J?�Q�A�X��_PK
   
��G�b�'�  n  ?   org/gradle/cli/CommandLineParser$UnknownOptionParserState.class�U�RA==�$a2@���(���"�"��UZ��`�$S��d��L�_�_\H�*~�����7���=�
B%q��������}������� �q[A�
:0�A��0�������i�Dq5�kr����C�w˺�&����e���z�ab�q�������
��[vJ%nWL[��F���!�ɕg��Y�6rk�k���X����>O��� ��6�E=}��9A{�g���N���)��Ҧ�>曖.^����]S��`�{j�HO�����~�噎���-�p���e��}^^r�JI�=R7�٠�cS�����ޒ�{2�i�O�c��i�nی1^�#r�޲t1b7���?4�yv�o��=)L^�)a�FZ��֜�[���c9��*z�'̼�n$T� �BA��aF���&���0����0�� �� �mGd��6�a4�j3��
C'/�n{._�V����Ƶձ+1D��X��
�UR����%�H�}4���+�.L�_c���=�������:��"��L3�2��}F7��>���L�` g %.d�J\)�+QL��gq.�:K^dI�׵{""¾��j�4�e8�� �����ț#��u`��@�T�s䥓�?|p��P{��������f�r�	m���EhQr�!�"X�O�ٯ��UV�H/.\� 鋘6�? �9R�?SͩUÕ@�F�cH�:�U�1�-�N���cjX�c��4Uy�<blfq=��PK
   
��G"z�Z�  �
  &   org/gradle/cli/CommandLineOption.class�V[s�V��˱E�� �c���P�@H��IC�E�UGT�\Y�����������S¤�t��Cg�'�C�~GVdٲK���Ξ՞�ow�=������m;0%c:�6Lu`;���f���x�%p�	��-�xK��	܆*cAF>��ꩂ�4!�#��x,Ơ�pG(ߍ��%q�KFI�{d����Y��5~G���*�n��4gXBǔ^4U�bk�ֿ�n
�,��[7��'yBQ�beI3��%��5�r�&�u#q2Q��y[w�HH��SY�J��W�ɬj��J��f��@��H7�h[�Ҭ�,J8�ڲ����-�7�ܨ�����q��&��n��ԝ�R� �1G��C���
�(ONT�4{Z����j̨�.��2jyi���~\P�Sǲ5�,��c����KBO}p���,�̮�V�!^Ԝ�UmH�(��Q�"lbQ-���#a("��Z�r<tN�kMG��k2S8�r'�?$���|����r�`f[Bo�Tt��	t�w���鰯5�L�ið����8[�WWH/Z�$�:aZA�f7���)�_���P��k��d��,�OG��×K�)�b�����{O������ lҴ��Z^d;�KFY���
��GFh*
�➌�
`L���@BwcOd|��#|��\��e�;բ�
�a��O���B�����1_kB�`/�[
�`��kwu_bT�W�Z�7"vߋ*U�|r᎖w\R̬�v_�X:{���z�=
_b�wi��u�\fɰ���{B�Ϩ�]ꇥ
����Q�Ղ�c:l+UK%ͤ�`�ii=nQC3�������������9VU%"
!s����a����֖��v������(��e`��k��M������p��ʮsM
.�vY~�%y��!j~G;"\/e�B�<C��S�?A$�3�=A����+�I�rpb�������/��@i�������ڑ�sb|����Ћ沛X���<�1Z�D�-������<���Wp���
G0��N%�c��U�q?�W)���jV9���:��k�s�(��ܠU'�� U'#Խ����k\9��x��w�<�q]�T�|��יV�����rüΧs�aӑو�j��@�C<�`��I8����fN���99�Q�ɟ^��3t��]��^�s(Y�.c�,��s���������el��&7�w�(�
��6�������2�� � %�V���.�zه],�~vb�a���Y�]�9\'y�p��B/ʯy	�p�v�\��/�S�^v��3�^N�襘*��"�Zuv�+v7s�rN��y�H��
D�
Իۯ���~طZ��m��r�䒡��������K�L�@ə��B�nә�#�x�}�/�8}Z���4]����rt'�{d�s�yI	'����jbf�(֮�N��/�ձUJ��R��[g����|��5W��/PK
   
��G�l\ϧ  �  8   org/gradle/cli/CommandLineParser$OptionParserState.class�R]KA=�Y�vMM���w�)F)n_$"h@�
�<�6I���fVf'%����O�>����w6ivΝ����=w�����_|d���Ŧ���m���TҜ1d�{m��C�)���;B��ND��f��Q�ki���k�Ʉa����X�q��2�ЯܵRB7"�$�(��X�A�y/A7�A#�����>���;�:�|�(��F͹=�����U�&K��cEZ��P����|��yq-��
�S+·�\��U��|HQ�S$���� ���d�ú݄3�1T��M�Z�@wť�K)���	s�A�C��h�U0^]��<�.
�u�ʓGa(M"�%z�.업cg"W=��d1f���I��ٔd}���F����6,���O).a9Ţ�,se,~B���ٿŋ#�H<�V������
j�e�la���',~&Da�PK
   
��G[xn��  �  &   org/gradle/cli/ParsedCommandLine.class�W�W�F���D�Uǎ�&U�8�e9�$�x���&M�)qIpZ
k"O�g��ȉ[JK�Ѕ����}3���4	&?�7���o��W�;3Kָ1/3��9�;�|g��?���?�0�O��3���Q�q)����ģ"�N��ݩ&���I\��x<#�Y�K�F��\��e�'��ǋ2����/%Ў�d|9�nq|E�_���~Uh�&N~5����X~] |C��q��@��ěxK�|K<�-o�����f��M�rdyֶt�ȝ����:X�����Z��2�
ծZ����_G�eI5��.�Ȕi��Z(i��%}�qժh�	sqQ5
S���r,��9U���f��V����fS��Ɲ ���-�KZ�JiWlK���E�s��gJ����6�����n�I���)��5K=_�F��H�L�Mx��f���5�	�MBzʜWKgTKko����&�B�M	!}���:�c�i��Rv�t���*݌�����x&H;b/�� �&|��f��� �$t4Ұ\�Qq8@���ecKgE���Y+��l_P�KU��ƍ¤����t2V��eW���v�jK�j:p���
�H��W��V�Z�9�٠�+tt˺���,��Z=��6k�ћ�ի��n�� 7�k'��u;a�Jڼ÷8��d��%���gG���\��M���
Dբ�z�5�膹��UO�d�״�I ��B�A<����&��\�K����:@�������C�f��i����ĬY���c�h��&���i��6	]'��ZY���` ��>p�+�o(8��Y�:nY�� X�G�QW��we|W���}?�%d��73�5V�?��6�F�e�T���s	�q8����3����x[���S�/��������W�&S�wE>�����F��)L+� ��5Ve�F�'1����Gy݌`��Rͫ�W�5����O�ޞJo�0�LA����E�.ȸ��:V�������61'!�u�n�Q�úw��T����K7�rXr�̐[��1�E�0w�$D���Ϟ�C�όm

kV%�8��p��y'Ѓ��ZT�1жO~0��͢�˚QX|��l��%>o����w� l���(��t((�T���yN,�x�0^*QpK�Wj'DG�	�\��!����� ׇ6�����!$�>�!�À4�0d�)w��[�@�b�b��u1N��b���kP$L�װM�U�����Wl���V��
Gn"��{$G���^FH�b�p,�>"]���v�qv�οV��w�΄1��(|v3����A��?�w2�{1��0�]��/�~��^&I�<q��H�a�!��ыG�1�ǘ%�8�o��x�v�ǝ�!A���H�E�	�
��ΤO�IJ�|�B|�V�N�#�'t΅s�K����s!&1*ĸ���k��=��8������q�f	��;�����&��NG�\��#�=��CB����z�|����u6������u���ʝ��o��k>�����C9$�E��g�d��$}�uv�Nrg�5&<g��S���F2#�S��ӤX����LB����i�,������A�|�;"Am���Ȝ�2�BX�iL8
G�:��m���$�� �x0�$aO��I<���M�}�S�!ݵi�ɱ3��hdu�οë~
:H���nR��$쥯��X����	��e3~������S����;��i�%^v^�����hC:�7D�Z�������K��8��/
�z��_�ڴa�M�Q�@''3.`�C.?)��h������t���I6�D��%��E�O�F�(���F�e�\ߐ�2K�R]�AE�A0 �C4��n�eF�]ޙh}41��o�	ZL�*�Ғ�-"�vv;�Hr�	��ZJ�ً��g�
��Pmv������2��\��)Q�\�����z�n1=�!�,�y������/����(}�)�{�VLC��C��"C%s�PK
   
��G�A5l|    :   org/gradle/cli/ProjectPropertiesCommandLineConverter.class��KO�@��D|?Pâ��u�#Q�+�$�C;�1m�	�JW&.��(��1�D�,���9��v�o�/� ��[@yl汕G)��v�
}FHWkw�LS����!�]�nY�7�ZK:̿cJD������ZRy����s����V��;�H�+-��)���n�kS�#cruLX��gh|��B���j���F��Y���D���Ώ�%�L��%���且E*�_����?�ֈ:("�<�ڄbJՍ�	��؊t�f�^*K���
ߵ�
XU��V����i01�k
���p8��wZ��8T0g�?P�a�Λ�m����=���C
S�s����|	�1\���Z�q-}C�_�J��Eˉ�j��E+	��w'��PK
   
��G2lW�J    F   org/gradle/cli/CommandLineParser$CaseInsensitiveStringComparator.class�S]oA=3|,�b���YŊ�/���b �JbB$jB���f�mv�ƿ��ŗ��`|�G�,LC�{�ι��=w���o�4�<O2H�%�=��
Tt�j�nੁ}�dK9*8b��+'��;�]��w�I_zǢoS$�u�>���(>*��A[������U��e/��3n��3����̎�H�mߗ����zck쉡-���,�N�3�U?ϗ^i
e���;��^�{*΅egl͑��H�C9�Y�\���Y,�����X�
�������f��.Gg��a�b@H���FSϢ�/@�tO�L���]u(��#_k��#���ܩ7�o�na�ܾN2��5h�8)��Jeb[�1���1_b�7İ��#���j1lF�����ד�>�����^����I�>7ʢ0�u��I;�,
��W�/�pdiM� O�:���
䡟	I%�'���/;�����L��ق)�<�$O#�30��V�����Q+�q�<Nw0q�˽��w��o����~ Q�����/΁�Y�Ž�D;��H�bD��,��`��(��>��vC�#�B�7�>8�^C��~�,r��PK
   
��G���g  �*  &   org/gradle/cli/CommandLineParser.class�YxTյ^+�8��$� �<"�c� B��2DT�0��xHN���L�� Q���*�Q��`}W��VEI�Q���bm�}�>l����[�{o{�zk[������L&����={��������w����D4�O�����E>����-��N���S:�`ҩ�Y��ti]:����^��4�i���:��T��N�<B��:��<���<.�3�3Z�"����i<��د�$��3���L�I:]Ó}\,#g�g��L��4h��}|��3t
0���Ku�b�q����	Ȋ
�T
�*�;S>�DO�nv��s�S��\�s���tZ��5^�q�N�x�N��H�k�D���
Ku�Pf����t�0\�s|\'V9Of��h�H9_>�|!���E_�G�x���5n��*���kD�K|�(��j|�NW�u\ ��⊀�_��&���Zy��6�x�X�r���:����͢	�Ѣ�ŭ>n��v��L�d���h]ȌŬӘU��`$���mu��N3j�#Q��1M�3c֊p�
ǂ��N+��)�^��n;ӸK��Ñ]a{r��Y�Ƹ�4A�ŧ��`*p3�c1��gk�mj
{�m�[Q{4�q�i�9?����B�3�ˬ�H��׸3e��]f�ʐ�<���~�tk1��j����4��/߲�v����40�Wn3w�U]�`�*j�Y��V�qh^�4*b+��;�cTu��	���`[،wE��噳���!3�Ve3X�2m�j��-!��9�J�me0l��.\�f(�U�m�$����5��/��X�Q��T��(ڲ��*5�>�0X�1MB)�����[C,�]�K�\%����u�K6��:�ZѵBQ+#�fh�
��Ġ;��S>ao�gD|IJ��J"/?{���)K���ll��lQ�K�@p�k�	�l ��w7[ɀ�旤Q��&����3��	1���e�x�p2�^�Њ$�W,��f���HB��9�iȰ��T�A�/L
gnqי�1�:m^Wy^9��37=&3��;����
����Y9l�ays�@���
�㴒A��^W�V!�[��Nl�f4
w�CVMAs6l|r�%�%,�X�2ѭъc�9�#���_z�`Υ1�
N��{���ng߈d ��H�Ŋ&��� o��ɑ�4�Vc��k��
��i�� �c��Q���n�2�y��U�������@�C�8�dq~r4#3��r�����a�f��mA�𝖵�Ӑ-tچ�1��z��Ǔ�:��H��$m}]$���ǝB��<8&���H�j�60�S���+�e�1+U�x�v���O�
�L͈�V)2�����dcK�w$���ԧ��T*�fs��M�;s&����A�@���+���s��rPђd�@X�%~�R�,>�AŅo��BBo�tE�Q�	��ĢRt�m�A�Ӡ/�m�Aw2�O�^����æ��a��8n��ˠüzP>� 6x�k�&q�;�(wi���n�Ҡ��1�⡎�A���_e��+?#�����<��ZY}�������x�����
�� �ˎ-�o����
�?�71U)4��Y7����x���������`�m�E�o�; h%Kc�	�ŕe����.���C m5/�2K��iy���͕eH�48MV�ˣ�H��â���Y�HB3�u��{�>��C<�w� ?d��|��/�=?j|����l�6���&"�%~�����Q˂��f
���N�u�&��
~����g��ۭ�Tp�&��K��O�S[��F�n924~�(6���+�����g
>�Oi�c�O����Y�S���}q��������1�������y����)V\,>!i8��5~Q迁"%�Fڶ�!�]8�S�8j/{��3��fc�f��]܂" l�T�/���i��$j��p8/6[Z�#ikM�
'G�m�X܌Ƌw���3*fTtݪ���¯j��d��L�ɱ8u7G�q3����fۉ������f�X�)�T�eF�'��|�_7�
QdBɦ�{6u^U߽S��-��J
~��S��%\��h���C�8�K�p�����i���G
�O�F1wxp:��b����p��4����}��G���R/����ŭ�A	�q�p���:�UY?z�-���g��=���
h�ະtp�?"��@��4���t���4���4��Ə���(���J}�����T0qe�fl/�*_8x��n���q�����V?F��:��f�V��G���dQ�CY�A�'vt��XA�] X1�j��M4�������X��X"��JV8��#$�Ӷ��a������fdB�Y����s@ֳ��X��]��-��pޚ� J%+�2'�|Z�y�3+v�ܚ����]y��Z��?I��KU�f�Q����S�4���p[�]�)�	7D�I�+��6��~�6���v�=��NUҹp�#�ڢ����A�[�
��!q7c��ODh�$�J5煰�P����cC������`Ff"�m���Xs4�|���'��6���ۭne�����X�#	���k���A�:��ŋ�>�+��Az��/)�2{ �<�����dM�Ì�����O�kfP;�#\���2f�VZ���w��E��}�Q*�F��n�2ld/��-t�\��7*������błQ�%u�֌pU��OHz����Y�����K��xF/Gn���Eտ�q�W���-�{>Q�8�+��(��ڎ���k1���hY?y������^�=�܋���h)��s�>��������6�G�N,��\�ӛz)���5��ݽ4�� �BH&Z�����	t�0�f� ���!��Gӗ1gk���r�ʏ���T��W��*�Un�U.=B�b������������� ��w?�7�SASٳ���B�^:��F��SQSY�{iLCy�����w��8�C|Rz~�o��O���Cg�'���	=4���Ӥ&Y:��ߋ��:�����)=4������4��tEP���/��6e(f�������B�-����T-:KѺ=	R��{�)��G��0��ԇ)���TŋX��l�����D���MHT�ѻ	�5�����*��
�f����B<��Αvj�����9����Kn�������-��U?D��+��}ͨ�3Y�	�z��܆��|���Gh)t�IW71������e��|<����$�\*��R ߍ4�6�T�x-]N�ZKW���i+uS3]M-tYH�VDY=K��黴�~��(DR��0����N�H;���\Jq�G]�H;y#��m�͝t%參� ��Gi/?���h?O�毑Fߦ���O7��V�+����9���=H�nd�W�k��I��qz�G�l2�A3Þ�.6�iS���$�C�?���j���Ann�WJ��ӠӠ��և���z�k�9z=��;��1I�w2�{�����EnG�N�_Q=��'1FQ=���6Ľ��	z1�:s1"���!�l l��JJ.-\�Y�bq/X���b��%������M��������D��U�)��BJyf�x�ܪ��v������/}�����[���ł0�Zr���e19�& �����D[
/������7�w�5	q`{EG$�\���h�T@���'��5�x�^��b@��̥8$�s���Ӈ�
��Iy�yC�M��m�*���[�������|�V�5�z鼆����r���·�.�qW(k� ��8)��A�6���
�QH���(��b0Y���l.����u�K@�. R�~���}N�G�`v�&LO<}�Q����8����ZؾJy��a�
�`�����}t�K�g" ��h�=�v^	��$�%�ӊ<�O��im��!K.��3�d�X��֫�J������/PS�hԺ�=�ZH�g�R��\x��)Oy�;��^x�&�ᖧS!x%K@&	�G
�B��H��iF��B�J���N ���s�����!�>B�|L'Y�� ���|z���w�\��������]�.�J?�W���:��O�K��b8)`E� �%ds!�1z�5H���Co#<ߧi �'1�;���Ԋ���(��K5dC��
~ORiq����>���5���$��i�����\~�~D?F��Y�	4p�l���Fn����J3I�9hm=���� U[�/0f�x#��=��./ˉ���h�]�OW��f�{I�k�-\������&4=Q��x&�� ����9�Z��������R�V��&"s$�Я��y@��(���oh9}����Pn�+���G?�8*�u�z�ö��?�#܅ϝ頯��ֆ� �G��	�m�ed�'��'����E��Ѯ@�{v�G!�'Q�������-� �����KAT8��ے�۳��RH,��C��	�H6�~7 ��~Z�$�g/u�������T*��^�@�/�4�?�h�3 �C�O�m>���o("�N{0w-�r3=�81�E���IB�h�G�1��h�A��v�|��v7�*��8
����D
�O$���Wr"'߇L����X�V<#v?�UgW����a���K�wX��.ҝ��Qr��$���a1C2���ԭ�Z��?�v�PX�>Bn�3TV;N�g(���!��j��<���$}���E]���b�ݍ;[7�+�.C{�=h���g�^������u��u��v?�Ϣ��Eh?���7�s� �σ�&�7�n��o�? PK
   
��G_>ң  )  3   org/gradle/cli/CommandLineParser$AfterOptions.class��mO�P�����(���@����1Q|�Dt��1wkծ5wE�#�]|!�J���e<������1Mz�9���9��s۟��� PƊ����'IW1�!�y
&T�U,2��~��=��x6�x�
�!���f�u�'\�lku�g�!s����6��t��L�!��[/H��v�f�����e��׹[�zdL[N�!�e`��o�����y�Xuy�e�j��&!&��)�b����A��02]y��r��^����5Vf6R\4dr]��C���0Ԗ�Qi2�6{�{�J.��-�ߏ���޺���&�Ww{Ֆ/I�U0�ᘓ������*3Le	���o������1��3/��#��|]ˣ��%��D�3�����z���aS2�u7�X�M��c� ��q��a���A78yW��N���*%�1|�����a�����vť�-��N x����!�*J���B����)����>#� iISh��l�n|Aj�T��D�V����И��F��(�J��9���D��RR;��%܊1�C!˩�1������(|G���FJf��� �Km���t�$������F5k�j|EFߧjr�	dY+���!kmrQ>���э=����e��p2�n��&0ft9\9E'�tN*��iϤ���K4�����9h�PK
   
��GG�f��  �  3   org/gradle/cli/CommandLineParser$OptionString.class�S�NA=����]ʗ(��U�-e)�'��#�
�X�lw��������D �����w�+B��s��;s�=����??~��� ���&c��AY�s)TҘ��a!

ei��,�pW�]�p_��8����=��[w��f��ns�a�k��璳b�v��0[�=כ)m1$j^C0d�lW���v����8Bv������-�(�w��A�P
;e(|��¯9<�Zk�ߴ�>o8ª;�U�Z-�6d�7��_8�'��_�A����y���K���B��kx� ���y>���E�ӂ4Ui
�1�z]A���@�Z����
Moz�~]�����{*�K��a:r�摎,b^�hx�c+ݥ���NC�:�`U�
L1���������r)����;{�2̜?3z�E9n���B���;;�e�s�����y�-���w�'i������<����M��I^NN�֤ʍЛ�h��8�Y�q�|��9wv��c
���9��1Ȩ(�`����(O�aʓ�b��mDm>�X1�#�Ka��~B�>FJ���g�!���6��l|;�4 k�a�C�ԍx0����r/iA���';`C��G�
�G¡���c�������#'3�0�@Ì���n�����m��aF⺙ig�M��1�*[Ĥ����=\E7i���PK
   
��Gx&�T`    ;   org/gradle/cli/AbstractPropertiesCommandLineConverter.class�V[wU�L2�悡5&�I
��i-4J��`�E��i+v�	�fpf�D�����{_�m\KW���u�� d��Y��\��g�o��}���_p?H8��"�%��ެH��O%�"ͧ���=	�IX��6���@�7�����=�q|�';�y �!��H��"r���k��7�b����0��tu�Vɫ�}%_&I c���bj|�
��f1\�f)^2�bY��Z<��lS)؛�QUM[S�e�RQ�"7�l�$T��XI=V:ܨښ�3��#�}�@����ڦ��Hu¥zG����z�YSkY,��V��9>	�ڦbZ����:c���듶�r�*�&2�M
n��8&��M2j�V��)�D�Ĝ2W5(	,MIz�n)嚺R���e9����hs�t��4%D�
������Tmg�����v������֭�t�tK����zAm"�
e7��t�v����٧��)���
�ٹ��Wv"�Q���ys�;����]�^W/y�p��F�,�w5��h}�ے�6&FOڕ�.�e�`Z�c�C��Q@Q�,_Q�+��W�Dh2��DDYF�CF��0���-6j�q��d�qț��t�+#�y:��*[�0����J��ZE����f�~]��r~��N����
���W�S�cXD�\�/�ô���.��'����Ƕk_����4��Ы�TS�!�� ��Wn�[�0���(���
�5��R�%��պ�<��a՝I�Sؾꢦ���.C�i�$�h���{w��fЪ�CM�x(�t�'9�%f���9���o=���,N=�M�柃�B&��;� ��ﭣ��� /
N?��gyO:�a�e�J�^�/F}�����o�����
��b
��b4���k ��t���"��t�Z�3
я��U�0�f�x���'y�S��r�
6 a�a��d���B�3/��)L��F}A����t�c^l?�$ͼA_�	!�7�Vk���g��렾@�����C����G��G�D��o�Ѕp�h.`�(���r��r�o���Y�J�]k��KIr��In�#����)����`�,r��|4��
���m�Y��YB��Y�-B������1>q��?PK
   
��G����     ,   org/gradle/cli/ParsedCommandLineOption.class�S[OA���-�"�\A(�-ʊ�P�1!iĤ�oC;Y�lw��-
�&����J�D}�G��.�I|��9���|�93��|�`+)dPPQL!�B�0��冊�I,�����-,&q[~器+��䲤⾊
{�lqWA���������W��-+��
�b^��
f�~����,C�z����2�$J�^YA4_�T[����W�jls��6)���5fn2G�s�y;���+�c����&�k��_2���U��`V]Rm4=a[T.ipoSP0�/��DND�=�t��=��Z���+��jp�S0�?�����
��KOr���bj����rj�������d�0�a�jW��K����p�
&N���&7�Y�����ָ�E��4W����l/���x�Y�9�2,�BS*jx�<�Iy��2=�4y�t	Z�c�<�N5��D�S)h���y�ec{�ר��Ύ���+��[���+G��9� �tGi`]�
�ɾ��]k4�}Lқ�Ѓ���.�w�N"N{j9��)RF�v@�x�x���!�_�죯�*�!(#F9#��<�b��õ��r-�!~��c$���!�?�_;B}�S'�������]q�4�j�T���ǩB՗���)���t������,��;��arА�&1ZxFiW�$�G���uw+��&9#�P���4f|tgC�R(+������d�c����ٶ�9��PK
   
��G���s�  �  =   org/gradle/cli/CommandLineParser$OptionAwareParserState.class�U�NQ=�]��v��~�b�
5ȧ
1$
��4���Z����֏G�	��MD�$>�e�[�ZҚE����̙s�w�������y�'��

:��iB���&1�!CŴ3*f5$0�b^E�!Ys�M��K�c2��\�(4��f�f[��\xf}�f�!�?���\t�]($z�r,�.�épx�|� l�uj6%;�f�Oxզ�Pɭq�%� ��v2�^���l��"P+�ܧ���8�ش�癄[�2۟��k���VM���a�*��W�`s�Q(��r���Q.���$��uv\�� y������;>ަVv[�fnY�x2=�Y��#����X�1�3:ncTŢ�%,�Xѱ�5��P��g�)�,���V5H3�K�;3'�<<���Jڵ�Sΐ!�/g��&�W;;�^ݸ�
v,l�y����/x��-�/�T���Q���Z�����]܉\E��/�˕=��l����k<���R�wg�rŁtZ.=E�Ph��h���+"J6mL���+"F~�OA-���,�� �f1B�娆�E��$5"�y	��
�3��@����A�U��'����2�P��*��=�QE�3�D5@�}C�2Pw�'3�y�=RH������I��8[��-vt��n��5��@� �$}_�+:��)�+�C�	���E��oPK
   
��G'H  g  )   org/gradle/cli/CommandLineConverter.class�QMK�@}���ԯ�'�"4
F�M)HQ
	޷��lI7�ݔ�6� ��M��B��y���a�����=�t��$S�
���l���)����8�A	{Oyb�
:�˄����3�I���5'��	JXd�T"qx��{a��/4���OR��1=��Q6�15	�ڹ���6���ƇE�Wb�Rh��{'�q�j<��R��:�O2��%��z	\߮X���Λ�����v����+��T�@h`u�-B-��٬e�65�_TܚJ�VpX�k���{�PK
   
��GC���  |  <   org/gradle/cli/CommandLineParser$BeforeFirstSubCommand.class�VmSW~n�pq�hH
���H�@^A�@��m�6طMX��d��l��?���δ����[g����?��swc&��q�_�˹�=��Ϟ{����o/ �q_�Y̝�f^Fse,�Z K�����,s��
2V9���qS�>�1�M����8������zt�a,gZ�T�Rw�Z�\�SY�VS�ݜnh�U��YB/��n/1�Oz�_G8��6\fj�Aʚ��a�j�J�uG-U�ʙe���Z��7����^���[u��(5}2(��Y٪Z�k��$���N(@���y�A�(تe�ٺip|�0���X�h������5UU�J�`[�Q�tZ���w�&~ժ�:�ѷ3<�����MS�n9j�*ߌq\6��G��J*y�N;��{2#��hf���c�f�*S����L��È�a��q�!y"���
��Ǘ
��a�xI���b���t�xh���.�x��%0&X}�?:��Ui�4�^}R֚��B�e���*�"n�E&������̗he��;*J"b�����6�8�p����Õ7��rC.?V-�0��fO~��^y�뱉��6{*B�^*Y-��z=:7C��R�yʍհ�j���H�C*d�f��N�͔W{�M���^�p���C���M��Pz�kt��_gB�R���,�]x�;^�V�w�+���e��������M��+"��
�;�����������z�f7i��^���/O���3�}x���b�]C[B�]�d�5�c�|�Q�%�G����7�G���O�=�m�~�����}�҈�'Ү��O�C.�i�^y���b���;����3�0�r��ؑ�^!�yڢ{��f��
��U��5$�:2l�ދ9��e�X���	Lm1���� �}��IbR$Y�y�<�H�sG-v�V�p��01�
�1K�3gt��K�.�c�{\q|\Ňԯ�vg�9|�,Q��%�\^@�^�)��x.g!��E�0��I>|M�7��PK
   
��G� ;�|  �  9   org/gradle/cli/SystemPropertiesCommandLineConverter.class���J�@��ثm���j�E�5BDą�R/P�~�ӑ$&�B�JW���'i�A�Y�3���͜�����l� �"l�Y��l�E �<&�	d���@���H��g�L��{:r�R�s�:C*X4NĬ����Q�۴;hZ3a ѽ�G!]���G�v�7S"�5eb
o}ɸG�����tFM�z�9��y���~X{()spL`7e.�KV,
�TXxɢ����fDT� E�G��P�W��Jm�h~���49A�jx��Ѱ
��s�h��
gԙ�n8��5��]�.F�Ԓ�s�9��Q��΢���*�s�/@�Ug	J*�c�e+s��+1�
��$p�����6���/t-�,�;�h-�.�Z
�>k�Z�PK
   
��G-h�  �  2   org/gradle/cli/CommandLineParser$ParserState.class�S�o�P�N)s��T4706�|3M$���m	f{�@ú�[s�-�?���&>��G�m�d�iҞ���|����~�p �F�62�΢j�v,�-4�W���ׄT�qB0;��%�v=�^����(�>{J�` ��<mO�ft慄ܱP��z��ؙ� ��:�C�[�@���C�u��t��Xȡ���U��ۄ�X|��l���S���^K��B��^�<9j7N	Y/�
I	5�,g�+��B��K(&�
ާs��)���l���a �Ⱦ����s
�T�;9��$�P�/�:h�m����M�{����=������n(Y�U6�[M{y��h�E�[�=��߀���� "�X~������(�l�{�hp�/pe�Y��+�X0�x	w�rX�F����+l�f���ȱ�OX�&3A1*�L@ڜeh�f)���[0�1�F������pb��(C_�G�욱��mf;S�(�,h��I�K�Ƥ������5�4���=���p�e)>�aŴ9���,�D!�PK
   
��GF��=  �	  ;   org/gradle/cli/CommandLineParser$AfterFirstSubCommand.class�V�R�P�N[�J�;be�-��x)U.R(XE�_��(	��| �gtP�q�猏�C8�I
K��0㟳{v�|gw��9��|� �E͈4�т�H�A/�pK����\3*B���;�ExqO���H0ԛo�B �Нҍ\$gȫy%�ͫ����!k�)USd��q�W5ռ�0��l���%^-���-1x�������[+��T^ɓğҳr~I6T�/
=�]���kS1&U�`f�V��Ҍ�)F2/

Y�8�8���f-�IMy$M��1e�Lo���	H2�ڼ})M�zٛZ��ʑ���"�P�\�\���y6����O\}9ݲ'�JBKeվ�m���g���ËY��z�K�/��Z�1�}��*�*���2��	~���C��G&1%`Z�3t9e�a�ёgں�okǔ@g%��K�����u�`�|G%E�5e�uS閻5ϗ4���ڪ���۲����b��}B��\EU��6�:��Y�P�F�J�jh�>�3�L5��7��8"��)&CO��H��)Ys�l�H���#��?n��A;�ΙZ�w��QW+��S��^ͼ8��W��'AE��[�=�|>>Јs�����6�M��MT
�>��w��H{�im�:��:�^�A�:HG�8�����d"���F��x�&����O�+�\��������AX��!�vѸ�q�����
�H��W6������9Z���e�+˝�}с;	\G7���Ĺ(�(�	����]LSh��`��c����B	�p .�������9B��j��1�D'(����$�U��<"z���@'bj�'���]�GZf��<�_PK
   
��Gi�} F   D      gradle-cli-classpath.propertiesS��O)�IUHIM���,����R���SpIMV02T02�24�22Ppv
Q0204�*(��JM.)��**�+��M�� PK
   *��G           	          �A    META-INF/PK
   *��Gו�R?   U              ��)   META-INF/MANIFEST.MFPK
   ��G                     �A�   org/PK
   ��G                     �A�   org/gradle/PK
   ��G                     �A�   org/gradle/wrapper/PK
   ��Gh�df�   �   #           ��  org/gradle/wrapper/Download$1.classPK
   ��G�ޅ�  p  D           ��   org/gradle/wrapper/Download$SystemPropertiesProxyAuthenticator.classPK
   ��G��Xs�   �   "           ��v  org/gradle/wrapper/IDownload.classPK
   ��G�z�\  Q  -           ��`  org/gradle/wrapper/GradleUserHomeLookup.classPK
   ��G�]��  �
  3           ��  org/gradle/wrapper/ExclusiveFileAccessManager.classPK
   ��G�
^F�  �  -           ��  org/gradle/wrapper/WrapperConfiguration.classPK
   ��GQ}i�  
  0           ���  org/gradle/wrapper/SystemPropertiesHandler.classPK
   ��G�y0�V                ��,  org/gradle/wrapper/Logger.classPK
   ��G�r��  n  &           ���  org/gradle/wrapper/PathAssembler.classPK
   ��G8޶�  �)              ��   org/gradle/wrapper/Install.classPK
   ��G��L��  �	  -           ��q3  org/gradle/wrapper/BootstrapMainStarter.classPK
   ��GHַ$�
  #  (           ���8  org/gradle/wrapper/WrapperExecutor.classPK
   ��G����
  B  *           ���C  org/gradle/wrapper/GradleWrapperMain.classPK
   ��G��x�  �  "           ���N  org/gradle/wrapper/Install$1.classPK
   ��Gj j��  V  8           ��sU  org/gradle/wrapper/PathAssembler$LocalDistribution.classPK
   ��G�cJ  K  !           ��}W  org/gradle/wrapper/Download.classPK
   ��G�>�P   N   #           ���_  gradle-wrapper-classpath.propertiesPK
   
��G$ٖe�                ��f`  build-receipt.propertiesPK
   
��G                     �Aja  org/gradle/cli/PK
   
��G����<  S  1           ���a  org/gradle/cli/AbstractCommandLineConverter.classPK
   
��G2_e��   �   (           ��$d  org/gradle/cli/CommandLineParser$1.classPK
   
��GRB	�  �  <           ��e  org/gradle/cli/CommandLineParser$MissingOptionArgState.classPK
   
��G��M2�  �  =           ��h  org/gradle/cli/CommandLineParser$OptionStringComparator.classPK
   
��G�#
�G  K  1           ���j  org/gradle/cli/CommandLineArgumentException.classPK
   
��G?h��  �  =           ���l  org/gradle/cli/CommandLineParser$KnownOptionParserState.classPK
   
��Gk��  �  7           ���t  org/gradle/cli/CommandLineParser$OptionComparator.classPK
   
��G�b�'�  n  ?           ���w  org/gradle/cli/CommandLineParser$UnknownOptionParserState.classPK
   
��G"z�Z�  �
  &           ��{  org/gradle/cli/CommandLineOption.classPK
   
��G�l\ϧ  �  8           ����  org/gradle/cli/CommandLineParser$OptionParserState.classPK
   
��G[xn��  �  &           ����  org/gradle/cli/ParsedCommandLine.classPK
   
��G�A5l|    :           ����  org/gradle/cli/ProjectPropertiesCommandLineConverter.classPK
   
��G2lW�J    F           ����  org/gradle/cli/CommandLineParser$CaseInsensitiveStringComparator.classPK
   
��G���g  �*  &           ��C�  org/gradle/cli/CommandLineParser.classPK
   
��G_>ң  )  3           ����  org/gradle/cli/CommandLineParser$AfterOptions.classPK
   
��GG�f��  �  3           ����  org/gradle/cli/CommandLineParser$OptionString.classPK
   
��Gx&�T`    ;           ��ŧ  org/gradle/cli/AbstractPropertiesCommandLineConverter.classPK
   
��G����     ,           ��~�  org/gradle/cli/ParsedCommandLineOption.classPK
   
��G���s�  �  =           ����  org/gradle/cli/CommandLineParser$OptionAwareParserState.classPK
   
��G'H  g  )           ��w�  org/gradle/cli/CommandLineConverter.classPK
   
��GC���  |  <           ��׳  org/gradle/cli/CommandLineParser$BeforeFirstSubCommand.classPK
   
��G� ;�|  �  9           ���  org/gradle/cli/SystemPropertiesCommandLineConverter.classPK
   
��G-h�  �  2           ����  org/gradle/cli/CommandLineParser$ParserState.classPK
   
��GF��=  �	  ;           ��E�  org/gradle/cli/CommandLineParser$AfterFirstSubCommand.classPK
   
��Gi�} F   D              ��ۿ  gradle-cli-classpath.propertiesPK    1 1   ^�    
</file>
<file path="android/gradle/wrapper/gradle-wrapper.properties">
distributionBase=GRADLE_USER_HOME
distributionPath=wrapper/dists
zipStoreBase=GRADLE_USER_HOME
zipStorePath=wrapper/dists
distributionUrl=https\://services.gradle.org/distributions/gradle-8.12-all.zip

</file>
<file path="android/.gitignore">
gradle-wrapper.jar
/.gradle
/captures/
/gradlew
/gradlew.bat
/local.properties
GeneratedPluginRegistrant.java
.cxx/

# Remember to never publicly share your keystore.
# See https://flutter.dev/to/reference-keystore
key.properties
**/*.keystore
**/*.jks

</file>
<file path="android/build.gradle.kts">
allprojects {
    repositories {
        google()
        mavenCentral()
    }
}

val newBuildDir: Directory = rootProject.layout.buildDirectory.dir("../../build").get()
rootProject.layout.buildDirectory.value(newBuildDir)

subprojects {
    val newSubprojectBuildDir: Directory = newBuildDir.dir(project.name)
    project.layout.buildDirectory.value(newSubprojectBuildDir)
}
subprojects {
    project.evaluationDependsOn(":app")
}

tasks.register<Delete>("clean") {
    delete(rootProject.layout.buildDirectory)
}

</file>
<file path="android/gradle.properties">
org.gradle.jvmargs=-Xmx8G -XX:MaxMetaspaceSize=4G -XX:ReservedCodeCacheSize=512m -XX:+HeapDumpOnOutOfMemoryError
android.useAndroidX=true
android.enableJetifier=true

</file>
<file path="android/gradlew">
#!/usr/bin/env bash

##############################################################################
##
##  Gradle start up script for UN*X
##
##############################################################################

# Add default JVM options here. You can also use JAVA_OPTS and GRADLE_OPTS to pass JVM options to this script.
DEFAULT_JVM_OPTS=""

APP_NAME="Gradle"
APP_BASE_NAME=`basename "$0"`

# Use the maximum available, or set MAX_FD != -1 to use that value.
MAX_FD="maximum"

warn ( ) {
    echo "$*"
}

die ( ) {
    echo
    echo "$*"
    echo
    exit 1
}

# OS specific support (must be 'true' or 'false').
cygwin=false
msys=false
darwin=false
case "`uname`" in
  CYGWIN* )
    cygwin=true
    ;;
  Darwin* )
    darwin=true
    ;;
  MINGW* )
    msys=true
    ;;
esac

# Attempt to set APP_HOME
# Resolve links: $0 may be a link
PRG="$0"
# Need this for relative symlinks.
while [ -h "$PRG" ] ; do
    ls=`ls -ld "$PRG"`
    link=`expr "$ls" : '.*-> \(.*\)`
    if expr "$link" : '/.*' > /dev/null; then
        PRG="$link"
    else
        PRG=`dirname "$PRG"`"/$link"
    fi
done
SAVED="`pwd`"
cd "`dirname \"$PRG\"`/" >/dev/null
APP_HOME="`pwd -P`"
cd "$SAVED" >/dev/null

CLASSPATH=$APP_HOME/gradle/wrapper/gradle-wrapper.jar

# Determine the Java command to use to start the JVM.
if [ -n "$JAVA_HOME" ] ; then
    if [ -x "$JAVA_HOME/jre/sh/java" ] ; then
        # IBM's JDK on AIX uses strange locations for the executables
        JAVACMD="$JAVA_HOME/jre/sh/java"
    else
        JAVACMD="$JAVA_HOME/bin/java"
    fi
    if [ ! -x "$JAVACMD" ] ; then
        die "ERROR: JAVA_HOME is set to an invalid directory: $JAVA_HOME

Please set the JAVA_HOME variable in your environment to match the
location of your Java installation."
    fi
else
    JAVACMD="java"
    which java >/dev/null 2>&1 || die "ERROR: JAVA_HOME is not set and no 'java' command could be found in your PATH.

Please set the JAVA_HOME variable in your environment to match the
location of your Java installation."
fi

# Increase the maximum file descriptors if we can.
if [ "$cygwin" = "false" -a "$darwin" = "false" ] ; then
    MAX_FD_LIMIT=`ulimit -H -n`
    if [ $? -eq 0 ] ; then
        if [ "$MAX_FD" = "maximum" -o "$MAX_FD" = "max" ] ; then
            MAX_FD="$MAX_FD_LIMIT"
        fi
        ulimit -n $MAX_FD
        if [ $? -ne 0 ] ; then
            warn "Could not set maximum file descriptor limit: $MAX_FD"
        fi
    else
        warn "Could not query maximum file descriptor limit: $MAX_FD_LIMIT"
    fi
fi

# For Darwin, add options to specify how the application appears in the dock
if $darwin; then
    GRADLE_OPTS="$GRADLE_OPTS \"-Xdock:name=$APP_NAME\" \"-Xdock:icon=$APP_HOME/media/gradle.icns\""
fi

# For Cygwin, switch paths to Windows format before running java
if $cygwin ; then
    APP_HOME=`cygpath --path --mixed "$APP_HOME"`
    CLASSPATH=`cygpath --path --mixed "$CLASSPATH"`
    JAVACMD=`cygpath --unix "$JAVACMD"`

    # We build the pattern for arguments to be converted via cygpath
    ROOTDIRSRAW=`find -L / -maxdepth 1 -mindepth 1 -type d 2>/dev/null`
    SEP=""
    for dir in $ROOTDIRSRAW ; do
        ROOTDIRS="$ROOTDIRS$SEP$dir"
        SEP="|"
    done
    OURCYGPATTERN="(^($ROOTDIRS))"
    # Add a user-defined pattern to the cygpath arguments
    if [ "$GRADLE_CYGPATTERN" != "" ] ; then
        OURCYGPATTERN="$OURCYGPATTERN|($GRADLE_CYGPATTERN)"
    fi
    # Now convert the arguments - kludge to limit ourselves to /bin/sh
    i=0
    for arg in "$@" ; do
        CHECK=`echo "$arg"|egrep -c "$OURCYGPATTERN" -`
        CHECK2=`echo "$arg"|egrep -c "^-"`                                 ### Determine if an option

        if [ $CHECK -ne 0 ] && [ $CHECK2 -eq 0 ] ; then                    ### Added a condition
            eval `echo args$i`=`cygpath --path --ignore --mixed "$arg"`
        else
            eval `echo args$i`="\"$arg\""
        fi
        i=$((i+1))
    done
    case $i in
        (0) set -- ;;
        (1) set -- "$args0" ;;
        (2) set -- "$args0" "$args1" ;;
        (3) set -- "$args0" "$args1" "$args2" ;;
        (4) set -- "$args0" "$args1" "$args2" "$args3" ;;
        (5) set -- "$args0" "$args1" "$args2" "$args3" "$args4" ;;
        (6) set -- "$args0" "$args1" "$args2" "$args3" "$args4" "$args5" ;;
        (7) set -- "$args0" "$args1" "$args2" "$args3" "$args4" "$args5" "$args6" ;;
        (8) set -- "$args0" "$args1" "$args2" "$args3" "$args4" "$args5" "$args6" "$args7" ;;
        (9) set -- "$args0" "$args1" "$args2" "$args3" "$args4" "$args5" "$args6" "$args7" "$args8" ;;
    esac
fi

# Split up the JVM_OPTS And GRADLE_OPTS values into an array, following the shell quoting and substitution rules
function splitJvmOpts() {
    JVM_OPTS=("$@")
}
eval splitJvmOpts $DEFAULT_JVM_OPTS $JAVA_OPTS $GRADLE_OPTS
JVM_OPTS[${#JVM_OPTS[*]}]="-Dorg.gradle.appname=$APP_BASE_NAME"

exec "$JAVACMD" "${JVM_OPTS[@]}" -classpath "$CLASSPATH" org.gradle.wrapper.GradleWrapperMain "$@"

</file>
<file path="android/gradlew.bat">
@if "%DEBUG%" == "" @echo off
@rem ##########################################################################
@rem
@rem  Gradle startup script for Windows
@rem
@rem ##########################################################################

@rem Set local scope for the variables with windows NT shell
if "%OS%"=="Windows_NT" setlocal

@rem Add default JVM options here. You can also use JAVA_OPTS and GRADLE_OPTS to pass JVM options to this script.
set DEFAULT_JVM_OPTS=

set DIRNAME=%~dp0
if "%DIRNAME%" == "" set DIRNAME=.
set APP_BASE_NAME=%~n0
set APP_HOME=%DIRNAME%

@rem Find java.exe
if defined JAVA_HOME goto findJavaFromJavaHome

set JAVA_EXE=java.exe
%JAVA_EXE% -version >NUL 2>&1
if "%ERRORLEVEL%" == "0" goto init

echo.
echo ERROR: JAVA_HOME is not set and no 'java' command could be found in your PATH.
echo.
echo Please set the JAVA_HOME variable in your environment to match the
echo location of your Java installation.

goto fail

:findJavaFromJavaHome
set JAVA_HOME=%JAVA_HOME:"=%
set JAVA_EXE=%JAVA_HOME%/bin/java.exe

if exist "%JAVA_EXE%" goto init

echo.
echo ERROR: JAVA_HOME is set to an invalid directory: %JAVA_HOME%
echo.
echo Please set the JAVA_HOME variable in your environment to match the
echo location of your Java installation.

goto fail

:init
@rem Get command-line arguments, handling Windowz variants

if not "%OS%" == "Windows_NT" goto win9xME_args
if "%@eval[2+2]" == "4" goto 4NT_args

:win9xME_args
@rem Slurp the command line arguments.
set CMD_LINE_ARGS=
set _SKIP=2

:win9xME_args_slurp
if "x%~1" == "x" goto execute

set CMD_LINE_ARGS=%*
goto execute

:4NT_args
@rem Get arguments from the 4NT Shell from JP Software
set CMD_LINE_ARGS=%$

:execute
@rem Setup the command line

set CLASSPATH=%APP_HOME%\gradle\wrapper\gradle-wrapper.jar

@rem Execute Gradle
"%JAVA_EXE%" %DEFAULT_JVM_OPTS% %JAVA_OPTS% %GRADLE_OPTS% "-Dorg.gradle.appname=%APP_BASE_NAME%" -classpath "%CLASSPATH%" org.gradle.wrapper.GradleWrapperMain %CMD_LINE_ARGS%

:end
@rem End local scope for the variables with windows NT shell
if "%ERRORLEVEL%"=="0" goto mainEnd

:fail
rem Set variable GRADLE_EXIT_CONSOLE if you need the _script_ return code instead of
rem the _cmd.exe /c_ return code!
if  not "" == "%GRADLE_EXIT_CONSOLE%" exit 1
exit /b 1

:mainEnd
if "%OS%"=="Windows_NT" endlocal

:omega

</file>
<file path="android/local.properties">
sdk.dir=/Users/varyable/Library/Android/sdk
flutter.sdk=/Users/varyable/Workspace/flutter
</file>
<file path="android/settings.gradle.kts">
pluginManagement {
    val flutterSdkPath = run {
        val properties = java.util.Properties()
        file("local.properties").inputStream().use { properties.load(it) }
        val flutterSdkPath = properties.getProperty("flutter.sdk")
        require(flutterSdkPath != null) { "flutter.sdk not set in local.properties" }
        flutterSdkPath
    }

    includeBuild("$flutterSdkPath/packages/flutter_tools/gradle")

    repositories {
        google()
        mavenCentral()
        gradlePluginPortal()
    }
}

plugins {
    id("dev.flutter.flutter-plugin-loader") version "1.0.0"
    id("com.android.application") version "8.7.3" apply false
    id("org.jetbrains.kotlin.android") version "2.1.0" apply false
}

include(":app")

</file>
<file path="docs/COPILOT_INSTRUCTIONS.md">
# Flutter + Supabase + Node.js Edge Functions - Copilot Instructions

## Project Overview

This project uses Flutter for mobile/web frontend, Supabase for backend services (database, auth, storage), and Node.js Edge Functions for serverless business logic.

## 🏗️ Architecture Principles

### Flutter Frontend

- Use **BLoC pattern** for state management with `flutter_bloc`
- Implement **Repository pattern** for data layer abstraction
- Follow **Clean Architecture** principles (presentation, domain, data layers)
- Use **Dependency Injection** with `get_it` and `injectable`
- Implement proper **error handling** with custom exceptions

### Supabase Backend

- Use **Row Level Security (RLS)** for all tables
- Implement **database triggers** for audit trails and data validation
- Use **Edge Functions** for complex business logic that shouldn't be on client
- Leverage **Supabase Auth** for user management
- Use **Supabase Storage** for file uploads with proper security policies

### Node.js Edge Functions

- Write **TypeScript** for type safety
- Use **functional programming** patterns where possible
- Implement proper **error handling** with structured responses
- Use **Supabase client** with service role for database operations
- Follow **RESTful API** conventions

## 📱 Flutter Best Practices

### Project Structure

```
lib/
├── core/
│   ├── constants/
│   ├── errors/
│   ├── network/
│   └── utils/
├── features/
│   └── [feature_name]/
│       ├── data/
│       │   ├── datasources/
│       │   ├── models/
│       │   └── repositories/
│       ├── domain/
│       │   ├── entities/
│       │   ├── repositories/
│       │   └── usecases/
│       └── presentation/
│           ├── bloc/
│           ├── pages/
│           └── widgets/
└── main.dart
```

### State Management (BLoC)

```dart
// Always use proper event/state classes
class AuthBloc extends Bloc<AuthEvent, AuthState> {
  AuthBloc({required this.authRepository}) : super(AuthInitial()) {
    on<AuthLoginRequested>(_onLoginRequested);
    on<AuthLogoutRequested>(_onLogoutRequested);
  }

  Future<void> _onLoginRequested(
    AuthLoginRequested event,
    Emitter<AuthState> emit,
  ) async {
    emit(AuthLoading());
    try {
      final user = await authRepository.signIn(event.email, event.password);
      emit(AuthSuccess(user));
    } catch (e) {
      emit(AuthFailure(e.toString()));
    }
  }
}
```

### Error Handling

```dart
// Custom exceptions
class ServerException implements Exception {
  final String message;
  const ServerException(this.message);
}

// Failure classes for UI
abstract class Failure extends Equatable {
  final String message;
  const Failure(this.message);
}

class ServerFailure extends Failure {
  const ServerFailure(String message) : super(message);
}
```

### Supabase Client Setup

```dart
class SupabaseService {
  static final SupabaseClient _client = Supabase.instance.client;

  static SupabaseClient get client => _client;

  // Use this for authenticated requests
  static String? get accessToken => _client.auth.currentSession?.accessToken;

  // Helper for RLS queries
  static PostgrestFilterBuilder<T> authenticatedQuery<T>(String table) {
    return _client.from(table);
  }
}
```

### Repository Pattern

```dart
abstract class AuthRepository {
  Future<User> signIn(String email, String password);
  Future<void> signOut();
  Stream<AuthState> get authStateChanges;
}

class AuthRepositoryImpl implements AuthRepository {
  final SupabaseAuthDataSource remoteDataSource;

  const AuthRepositoryImpl({required this.remoteDataSource});

  @override
  Future<User> signIn(String email, String password) async {
    try {
      return await remoteDataSource.signIn(email, password);
    } on AuthException catch (e) {
      throw ServerException(e.message);
    }
  }
}
```

## 🗄️ Supabase Database Best Practices

### Table Design

```sql
-- Always use UUIDs for primary keys
CREATE TABLE profiles (
  id UUID REFERENCES auth.users ON DELETE CASCADE PRIMARY KEY,
  email TEXT UNIQUE NOT NULL,
  full_name TEXT,
  avatar_url TEXT,
  created_at TIMESTAMP WITH TIME ZONE DEFAULT NOW(),
  updated_at TIMESTAMP WITH TIME ZONE DEFAULT NOW()
);

-- Enable RLS
ALTER TABLE profiles ENABLE ROW LEVEL SECURITY;

-- Create policies
CREATE POLICY "Users can view own profile" ON profiles
  FOR SELECT USING (auth.uid() = id);

CREATE POLICY "Users can update own profile" ON profiles
  FOR UPDATE USING (auth.uid() = id);
```

### Triggers and Functions

```sql
-- Auto-update timestamps
CREATE OR REPLACE FUNCTION update_updated_at_column()
RETURNS TRIGGER AS $
BEGIN
  NEW.updated_at = NOW();
  RETURN NEW;
END;
$ language 'plpgsql';

CREATE TRIGGER update_profiles_updated_at
  BEFORE UPDATE ON profiles
  FOR EACH ROW EXECUTE FUNCTION update_updated_at_column();
```

### Security Policies

```sql
-- Secure file uploads
CREATE POLICY "Users can upload own avatars" ON storage.objects
  FOR INSERT WITH CHECK (
    bucket_id = 'avatars'
    AND auth.uid()::text = (storage.foldername(name))[1]
  );

CREATE POLICY "Anyone can view avatars" ON storage.objects
  FOR SELECT USING (bucket_id = 'avatars');
```

## ⚡ Edge Functions Best Practices

### Function Structure

```typescript
// supabase/functions/my-function/index.ts
import { serve } from "https://deno.land/std@0.168.0/http/server.ts";
import { createClient } from "https://esm.sh/@supabase/supabase-js@2";

interface RequestBody {
  // Define your request structure
}

interface ResponseBody {
  // Define your response structure
}

serve(async (req) => {
  try {
    // CORS handling
    if (req.method === "OPTIONS") {
      return new Response("ok", {
        headers: {
          "Access-Control-Allow-Origin": "*",
          "Access-Control-Allow-Headers":
            "authorization, x-client-info, apikey, content-type",
        },
      });
    }

    // Validate method
    if (req.method !== "POST") {
      return new Response(JSON.stringify({ error: "Method not allowed" }), {
        status: 405,
        headers: { "Content-Type": "application/json" },
      });
    }

    // Get auth token
    const authHeader = req.headers.get("Authorization")!;
    const token = authHeader.replace("Bearer ", "");

    // Initialize Supabase client
    const supabase = createClient(
      Deno.env.get("SUPABASE_URL") ?? "",
      Deno.env.get("SUPABASE_ANON_KEY") ?? "",
      { global: { headers: { Authorization: authHeader } } }
    );

    // Verify user
    const {
      data: { user },
      error: authError,
    } = await supabase.auth.getUser(token);
    if (authError || !user) {
      return new Response(JSON.stringify({ error: "Unauthorized" }), {
        status: 401,
        headers: { "Content-Type": "application/json" },
      });
    }

    // Parse request body
    const requestBody: RequestBody = await req.json();

    // Business logic here
    const result = await processBusinessLogic(requestBody, user.id, supabase);

    return new Response(JSON.stringify(result), {
      headers: {
        "Content-Type": "application/json",
        "Access-Control-Allow-Origin": "*",
      },
    });
  } catch (error) {
    console.error("Function error:", error);
    return new Response(JSON.stringify({ error: "Internal server error" }), {
      status: 500,
      headers: {
        "Content-Type": "application/json",
        "Access-Control-Allow-Origin": "*",
      },
    });
  }
});
```

### Database Operations in Functions

```typescript
// Use service role for admin operations
const supabaseAdmin = createClient(
  Deno.env.get("SUPABASE_URL") ?? "",
  Deno.env.get("SUPABASE_SERVICE_ROLE_KEY") ?? ""
);

// Always use transactions for multiple operations
const { data, error } = await supabase.rpc("process_complex_operation", {
  user_id: user.id,
  operation_data: requestBody,
});
```

## 🔒 Security Best Practices

### Flutter Security

```dart
// Store sensitive data securely
class SecureStorage {
  static const _storage = FlutterSecureStorage();

  static Future<void> storeToken(String token) async {
    await _storage.write(key: 'auth_token', value: token);
  }

  static Future<String?> getToken() async {
    return await _storage.read(key: 'auth_token');
  }
}

// Validate input data
class ValidationHelper {
  static String? validateEmail(String? email) {
    if (email == null || email.isEmpty) return 'Email is required';
    if (!RegExp(r'^[^@]+@[^@]+\.[^@]+').hasMatch(email)) {
      return 'Enter a valid email';
    }
    return null;
  }
}
```

### Supabase Security

```sql
-- Implement proper RLS policies
CREATE POLICY "Users can only access own data" ON user_data
  FOR ALL USING (user_id = auth.uid());

-- Validate data with constraints
ALTER TABLE user_profiles
ADD CONSTRAINT valid_email CHECK (email ~* '^[^@]+@[^@]+\.[^@]+);
```

## 🧪 Testing Guidelines

### Flutter Testing

```dart
// Unit tests for BLoC
group('AuthBloc', () {
  late AuthBloc authBloc;
  late MockAuthRepository mockAuthRepository;

  setUp(() {
    mockAuthRepository = MockAuthRepository();
    authBloc = AuthBloc(authRepository: mockAuthRepository);
  });

  blocTest<AuthBloc, AuthState>(
    'emits [AuthLoading, AuthSuccess] when login is successful',
    build: () => authBloc,
    act: (bloc) => bloc.add(AuthLoginRequested('test@test.com', 'password')),
    expect: () => [AuthLoading(), AuthSuccess(mockUser)],
  );
});

// Widget tests
testWidgets('LoginPage should display login form', (tester) async {
  await tester.pumpWidget(
    MaterialApp(
      home: BlocProvider(
        create: (_) => MockAuthBloc(),
        child: LoginPage(),
      ),
    ),
  );

  expect(find.byType(TextFormField), findsNWidgets(2));
  expect(find.byType(ElevatedButton), findsOneWidget);
});
```

### Edge Function Testing

```typescript
// Create test utilities
export const createTestSupabaseClient = () => {
  return createClient("http://localhost:54321", "test-anon-key");
};

// Test your business logic
Deno.test("should process user data correctly", async () => {
  const result = await processUserData(mockUserData);
  assertEquals(result.status, "success");
});
```

## 📊 Performance Optimization

### Flutter Performance

```dart
// Use const constructors
class MyWidget extends StatelessWidget {
  const MyWidget({Key? key}) : super(key: key);
}

// Implement proper list handling
ListView.builder(
  itemCount: items.length,
  itemBuilder: (context, index) => ItemWidget(item: items[index]),
)

// Use FutureBuilder/StreamBuilder properly
StreamBuilder<List<Item>>(
  stream: repository.getItemsStream(),
  builder: (context, snapshot) {
    if (snapshot.hasError) return ErrorWidget(snapshot.error!);
    if (!snapshot.hasData) return const LoadingWidget();
    return ItemsList(items: snapshot.data!);
  },
)
```

### Database Performance

```sql
-- Create proper indexes
CREATE INDEX idx_user_posts ON posts(user_id, created_at DESC);

-- Use proper pagination
SELECT * FROM posts
WHERE user_id = $1
ORDER BY created_at DESC
LIMIT $2 OFFSET $3;
```

## 🚀 Deployment & CI/CD

### Flutter Deployment

```yaml
# .github/workflows/flutter.yml
name: Flutter CI/CD
on:
  push:
    branches: [main]
  pull_request:
    branches: [main]

jobs:
  test:
    runs-on: ubuntu-latest
    steps:
      - uses: actions/checkout@v3
      - uses: subosito/flutter-action@v2
        with:
          flutter-version: "3.x"
      - run: flutter pub get
      - run: flutter analyze
      - run: flutter test
      - run: flutter build apk --release
```

### Supabase Migrations

```bash
# Always version your database changes
supabase migration new add_user_profiles_table
supabase db push
supabase gen types typescript --local > lib/database.types.ts
```

## 🔧 Development Workflow

### Environment Setup

```dart
// lib/core/config/app_config.dart
class AppConfig {
  static const String supabaseUrl = String.fromEnvironment('SUPABASE_URL');
  static const String supabaseAnonKey = String.fromEnvironment('SUPABASE_ANON_KEY');
  static const bool isProduction = bool.fromEnvironment('dart.vm.product');
}
```

### Code Organization

- Use **feature-first** folder structure
- Implement **barrel exports** for clean imports
- Follow **SOLID principles**
- Use **meaningful naming conventions**
- Write **comprehensive documentation**

### Git Workflow

```bash
# Feature branch workflow
git checkout -b feature/user-authentication
# Make changes
git add .
git commit -m "feat: implement user authentication with Supabase"
git push origin feature/user-authentication
# Create PR
```

## 📝 Code Review Checklist

### Flutter Code Review

- [ ] Proper state management implementation
- [ ] Error handling in place
- [ ] Widget tests written
- [ ] Performance optimizations applied
- [ ] Accessibility features implemented
- [ ] Code follows style guide

### Backend Code Review

- [ ] RLS policies implemented
- [ ] Input validation in place
- [ ] Proper error responses
- [ ] Security considerations addressed
- [ ] Database migrations tested
- [ ] Function tests written

## 🐛 Debugging Tips

### Flutter Debugging

```dart
// Use proper logging
import 'package:logger/logger.dart';

final logger = Logger();

// In your code
logger.d('Debug message');
logger.e('Error occurred', error, stackTrace);
```

### Supabase Debugging

```sql
-- Enable query logging
SET log_statement = 'all';

-- Check RLS policies
SELECT * FROM pg_policies WHERE tablename = 'your_table';
```

Remember: Always prioritize security, performance, and maintainability. Use TypeScript for Edge Functions, implement proper error handling, and follow Flutter's widget lifecycle best practices.

</file>
<file path="docs/DESIGN_SPECS.md">
# Foodster Design Specs

## 🧭 User Flows

### 1. Onboarding

- Select dietary preferences (e.g. vegetarian, keto, allergies)
- Set health goals (e.g. weight loss, muscle gain)
- Input household members (number, age group)
- Input grocery budget (optional)
- Choose country/region (for store support)

### 2. Main Dashboard

- View current week's meal plan
- Quick glance at budget status
- Tap to generate or edit plan
- Access grocery list or profile

### 3. Meal Planning Flow

1. Tap “Generate Plan”
2. Choose duration (week/month)
3. Review generated plan
4. Swap, remove, or re-roll recipes
5. Confirm plan → Grocery list is auto-generated

### 4. Grocery List

- Items grouped by category (Produce, Dairy, etc.)
- Quantity, price, and best store badge
- Tap to view alternatives or price history
- Option to tick off as you shop

### 5. Budget Tracking

- Set budget and track progress
- View estimated vs actual spending
- Get alerts when over budget
- “Optimize” button suggests cheaper swaps

### 6. Reverse Budgeting

- Input budget, household size, days
- Auto-generate a feasible meal plan
- Review and approve

---

## 🎨 UI Components

| Component         | Description                                 |
| ----------------- | ------------------------------------------- |
| Profile Card      | Shows user info, household, preferences     |
| Meal Card         | Dish name, image, macros, prep time, cost   |
| Grocery List Item | Name, quantity, price, tick/untick toggle   |
| Recipe Viewer     | Ingredients, steps, nutrition, swaps        |
| Budget Bar        | Visual budget meter with color zones        |
| Store Selector    | Map/store list with distance + pricing info |
| Notification      | Scheduled reminders for prep, shop, etc.    |

---

## 📱 Page Layouts

1. **Home/Dashboard**

   - Top: Greeting + Quick Stats (budget, meals)
   - Middle: Today’s meals preview
   - Bottom: Navigation bar (Home, Plan, List, Profile)

2. **Meal Plan**

   - Calendar or vertical day view
   - Add/edit meals per slot
   - Tap meal → detail view or swap

3. **Grocery List**

   - Collapsible sections (by category or store)
   - Floating button: Export / Print / Share

4. **Budget Page**
   - Monthly breakdown
   - Pie chart or bar graph of category spend
   - Recommendations for cost-saving

---

## 🔔 UX Notes

- Offline-first for grocery list & recipes
- Store distance is shown in minutes + km
- Save favorite meals or build from templates
- Accessibility: high contrast, screen reader support
- Dark mode supported

---

## 🛠️ Tools for UI Design

- Design System: [DESIGN_SYSTEM.json](./DESIGN_SYSTEM.json)
- Icons: Material Icons / Feather Icons
- Fonts: Google Fonts (e.g. Inter, Roboto)

---

**Foodster** aims for a balance between smart automation and user control, wrapped in a clean, intuitive interface.

</file>
<file path="docs/DESIGN_SYSTEM.json">
{
  "designSystem": {
    "name": "Foodster Design System",
    "version": "1.0",
    "description": "A comprehensive design system for a smart nutrition and grocery management app"
  },
  "colorPalette": {
    "primary": {
      "orange": "#FF6B35",
      "coral": "#FF8A65",
      "peach": "#FFB74D"
    },
    "secondary": {
      "darkNavy": "#1A1A2E",
      "charcoal": "#16213E",
      "slate": "#2D3748"
    },
    "accent": {
      "yellow": "#FFC107",
      "pink": "#E91E63",
      "purple": "#9C27B0"
    },
    "neutral": {
      "white": "#FFFFFF",
      "lightGray": "#F5F5F5",
      "mediumGray": "#9E9E9E",
      "darkGray": "#424242"
    },
    "gradient": {
      "primaryGradient": "linear-gradient(135deg, #FF6B35, #FFB74D)",
      "darkGradient": "linear-gradient(135deg, #1A1A2E, #2D3748)",
      "accentGradient": "linear-gradient(135deg, #E91E63, #9C27B0)"
    }
  },
  "typography": {
    "fontFamily": {
      "primary": "SF Pro Display",
      "secondary": "Roboto",
      "fallback": "system-ui, -apple-system, sans-serif"
    },
    "fontSizes": {
      "xs": "12px",
      "sm": "14px",
      "base": "16px",
      "lg": "18px",
      "xl": "20px",
      "2xl": "24px",
      "3xl": "30px",
      "4xl": "36px"
    },
    "fontWeights": {
      "light": 300,
      "regular": 400,
      "medium": 500,
      "semibold": 600,
      "bold": 700,
      "extrabold": 800
    },
    "hierarchy": {
      "h1": {
        "fontSize": "30px",
        "fontWeight": 700,
        "lineHeight": 1.2,
        "color": "white"
      },
      "h2": {
        "fontSize": "24px",
        "fontWeight": 600,
        "lineHeight": 1.3,
        "color": "white"
      },
      "h3": {
        "fontSize": "20px",
        "fontWeight": 600,
        "lineHeight": 1.4,
        "color": "white"
      },
      "body": {
        "fontSize": "16px",
        "fontWeight": 400,
        "lineHeight": 1.5,
        "color": "#9E9E9E"
      },
      "caption": {
        "fontSize": "14px",
        "fontWeight": 400,
        "lineHeight": 1.4,
        "color": "#9E9E9E"
      },
      "button": {
        "fontSize": "16px",
        "fontWeight": 600,
        "lineHeight": 1.2,
        "color": "white"
      }
    }
  },
  "spacing": {
    "xs": "4px",
    "sm": "8px",
    "md": "16px",
    "lg": "24px",
    "xl": "32px",
    "2xl": "48px",
    "3xl": "64px"
  },
  "borderRadius": {
    "none": "0px",
    "sm": "8px",
    "md": "12px",
    "lg": "16px",
    "xl": "24px",
    "full": "50%",
    "card": "20px",
    "button": "25px"
  },
  "shadows": {
    "none": "none",
    "sm": "0 2px 4px rgba(0, 0, 0, 0.1)",
    "md": "0 4px 12px rgba(0, 0, 0, 0.15)",
    "lg": "0 8px 24px rgba(0, 0, 0, 0.12)",
    "card": "0 4px 20px rgba(0, 0, 0, 0.08)",
    "button": "0 4px 12px rgba(255, 107, 53, 0.3)"
  },
  "components": {
    "appBar": {
      "height": "56px",
      "backgroundColor": "transparent",
      "elevation": 0,
      "titleStyle": {
        "fontSize": "20px",
        "fontWeight": 600,
        "color": "white",
        "textAlign": "center"
      },
      "iconColor": "white",
      "statusBarStyle": "light"
    },
    "navigationBar": {
      "height": "80px",
      "backgroundColor": "#1A1A2E",
      "itemCount": 5,
      "selectedColor": "#FF6B35",
      "unselectedColor": "#9E9E9E",
      "iconSize": "24px",
      "labelStyle": {
        "fontSize": "12px",
        "fontWeight": 500
      }
    },
    "cards": {
      "foodCard": {
        "borderRadius": "20px",
        "padding": "16px",
        "backgroundColor": "white",
        "shadow": "0 4px 20px rgba(0, 0, 0, 0.08)",
        "imageAspectRatio": "16:9",
        "titleStyle": {
          "fontSize": "18px",
          "fontWeight": 600,
          "color": "#1A1A2E"
        },
        "priceStyle": {
          "fontSize": "16px",
          "fontWeight": 700,
          "color": "#FF6B35"
        }
      },
      "categoryCard": {
        "borderRadius": "16px",
        "aspectRatio": "4:3",
        "overlay": "linear-gradient(to bottom, transparent 0%, rgba(0,0,0,0.7) 100%)",
        "titlePosition": "bottom-left",
        "titleStyle": {
          "fontSize": "24px",
          "fontWeight": 700,
          "color": "white"
        }
      },
      "orderCard": {
        "borderRadius": "12px",
        "padding": "16px",
        "backgroundColor": "#2D3748",
        "itemSpacing": "12px"
      }
    },
    "buttons": {
      "primary": {
        "backgroundColor": "#FF6B35",
        "borderRadius": "25px",
        "height": "50px",
        "shadow": "0 4px 12px rgba(255, 107, 53, 0.3)",
        "textStyle": {
          "fontSize": "16px",
          "fontWeight": 600,
          "color": "white"
        },
        "pressedOpacity": 0.8
      },
      "secondary": {
        "backgroundColor": "#1A1A2E",
        "borderRadius": "25px",
        "height": "50px",
        "textStyle": {
          "fontSize": "16px",
          "fontWeight": 600,
          "color": "white"
        }
      },
      "outlined": {
        "backgroundColor": "transparent",
        "borderColor": "#FF6B35",
        "borderWidth": "2px",
        "borderRadius": "25px",
        "height": "50px",
        "textStyle": {
          "fontSize": "16px",
          "fontWeight": 600,
          "color": "#FF6B35"
        }
      },
      "icon": {
        "size": "48px",
        "borderRadius": "24px",
        "backgroundColor": "rgba(255, 255, 255, 0.1)",
        "iconColor": "white",
        "iconSize": "24px"
      }
    },
    "inputs": {
      "textField": {
        "borderRadius": "12px",
        "backgroundColor": "#2D3748",
        "padding": "16px",
        "borderColor": "transparent",
        "focusedBorderColor": "#FF6B35",
        "textStyle": {
          "fontSize": "16px",
          "color": "white"
        },
        "hintStyle": {
          "fontSize": "16px",
          "color": "#9E9E9E"
        }
      },
      "searchBar": {
        "borderRadius": "25px",
        "backgroundColor": "rgba(255, 255, 255, 0.1)",
        "padding": "12px 20px",
        "iconColor": "#9E9E9E",
        "textStyle": {
          "fontSize": "16px",
          "color": "white"
        }
      }
    },
    "lists": {
      "spacing": "12px",
      "padding": "16px",
      "itemSeparator": "none"
    },
    "modals": {
      "borderRadius": "24px 24px 0px 0px",
      "backgroundColor": "#1A1A2E",
      "dragIndicator": {
        "width": "40px",
        "height": "4px",
        "backgroundColor": "#9E9E9E",
        "borderRadius": "2px"
      }
    },
    "indicators": {
      "pageIndicator": {
        "activeColor": "#FF6B35",
        "inactiveColor": "#9E9E9E",
        "size": "8px",
        "spacing": "8px"
      },
      "loading": {
        "color": "#FF6B35",
        "size": "24px"
      }
    }
  },
  "layouts": {
    "screen": {
      "backgroundColor": "#1A1A2E",
      "padding": "0px",
      "safeAreaHandling": true
    },
    "container": {
      "padding": "16px",
      "margin": "0px"
    },
    "section": {
      "marginBottom": "24px",
      "titleMarginBottom": "16px"
    },
    "grid": {
      "columns": 2,
      "spacing": "16px",
      "aspectRatio": "1:1"
    }
  },
  "illustrations": {
    "emptyStates": {
      "style": "minimal",
      "colorScheme": "monochromatic",
      "elements": ["geometric shapes", "simple icons", "subtle animations"],
      "backgroundColor": "transparent"
    },
    "onboarding": {
      "style": "flat illustration",
      "colorPalette": ["#FF6B35", "#FFB74D", "#1A1A2E"],
      "elements": ["abstract shapes", "lifestyle imagery", "gradient overlays"]
    }
  },
  "animations": {
    "transitions": {
      "duration": "300ms",
      "easing": "cubic-bezier(0.4, 0.0, 0.2, 1)"
    },
    "pageTransition": {
      "type": "slide",
      "duration": "250ms"
    },
    "buttonPress": {
      "scale": 0.95,
      "duration": "150ms"
    },
    "cardHover": {
      "elevation": "increased",
      "duration": "200ms"
    }
  },
  "patterns": {
    "navigation": {
      "type": "bottom_navigation",
      "style": "persistent",
      "iconStyle": "outlined"
    },
    "listItems": {
      "leadingIcon": true,
      "trailingAction": true,
      "dividers": false,
      "padding": "16px"
    },
    "headers": {
      "style": "large_title",
      "position": "center",
      "backgroundColor": "transparent"
    },
    "imageDisplay": {
      "aspectRatio": "16:9",
      "borderRadius": "12px",
      "placeholder": "gradient",
      "loadingStyle": "shimmer"
    }
  },
  "accessibility": {
    "minTouchTarget": "44px",
    "contrastRatio": "4.5:1",
    "focusIndicator": {
      "color": "#FF6B35",
      "width": "2px",
      "style": "solid"
    }
  },
  "responsive": {
    "breakpoints": {
      "mobile": "375px",
      "tablet": "768px",
      "desktop": "1024px"
    },
    "scalingFactor": {
      "mobile": 1.0,
      "tablet": 1.2,
      "desktop": 1.4
    }
  }
}

</file>
<file path="docs/IMPLEMENTATION_STATUS.md">
# Implementation Status

## Feature Requirements Status

| Feature ID | Title                      | Status     | Notes                                                    |
| ---------- | -------------------------- | ---------- | -------------------------------------------------------- |
| F001       | User Dietary Profile Setup | 🟡 Partial | Basic auth implemented, profile setup UI pending         |
| F004       | Nutrition Breakdown        | 🟢 Done    | Implemented with mock data, pending real Edamam API keys |
| F005       | Budget Input & Tracking    | 🟢 Done    | Fully implemented with local storage                     |
| F012       | Recipe Detail Viewer       | 🟡 Partial | Basic UI done, swap options pending                      |
| T001       | Flutter App (Mobile/Web)   | 🟢 Done    | iOS working, web export ready                            |
| T003       | Supabase Backend Services  | 🟡 Partial | Auth and Edge Functions set up                           |
| T005       | Nutrition & Recipe APIs    | 🟡 Partial | Edge Function ready, pending API keys                    |

## Status Legend

- 🔴 Not Started
- 🟡 Partial/In Progress
- 🟢 Complete
- ⭐ Complete with Enhancements

</file>
<file path="docs/PROJECT_REQUIREMENTS.md">
# Foodster Project Requirements

## Overview

Foodster is a smart nutrition and grocery management app that helps users:

- Create personalized meal plans
- Generate grocery lists
- Track and optimize budgets
- Find the best grocery prices
- Provide nutrition insights and alternatives

### Target Platforms

- ✅ Mobile (Flutter)
- ✅ Web (Flutter Web)
- 🕒 Desktop (Flutter Desktop - Future Phase)

### Tech Stack

- **Frontend**: Flutter (mobile, web, desktop)
- **Backend**: Node.js (Supabase Edge Functions)
- **Database/Auth/Realtime**: Supabase (PostgreSQL + Auth + Edge Functions)

---

## Feature Requirements

| Feature ID | Status | Title                            | Description                                                                                    | Priority | Dependencies                             |
| ---------- | ------ | -------------------------------- | ---------------------------------------------------------------------------------------------- | -------- | ---------------------------------------- |
| F001       | 🟡     | User Dietary Profile Setup       | Allow users to enter dietary needs, allergies, preferences, goals, and household info          | High     | None                                     |
| F002       | 🔴     | Meal Plan Generator              | Generate weekly/monthly meal schedules based on user profile                                   | High     | F001, Recipe API                         |
| F003       | 🔴     | Grocery List Generator           | Automatically create a grocery list from meal plans with quantities                            | High     | F002                                     |
| F004       | 🟢     | Nutrition Breakdown              | Provide calorie and macro/micronutrient info per meal and per day                              | Medium   | Nutrition API                            |
| F005       | 🟢     | Budget Input & Tracking          | Allow users to input a monthly grocery budget and track estimated vs actual costs              | High     | F001, F003, Store Price API              |
| F006       | 🔴     | Price Comparison & Store Locator | Show real-time price options for groceries, including nearest stores, distance, and best price | High     | Google Maps API, Store APIs              |
| F007       | 🔴     | Alternative Suggestions          | Recommend cheaper or healthier alternatives based on user profile and budget                   | Medium   | F001, F004, F005                         |
| F008       | 🔴     | Reverse Budgeting Mode           | Generate full meal plans based on user-defined budget and profile                              | High     | F001, F002, F005, Optimization Algorithm |
| F009       | 🔴     | Grocery List Organization        | Sort grocery items by category and optionally by store aisle for easy shopping                 | Medium   | F003                                     |
| F010       | 🔴     | Household Support                | Adjust ingredient quantities based on number of household members and age group                | High     | F001                                     |
| F011       | 🔴     | Notifications & Reminders        | Notify users of meal prep tasks, expiring budget, or incomplete shopping list                  | Low      | Push/local notifications                 |
| F012       | 🟡     | Recipe Detail Viewer             | Show recipe instructions, cooking time, tips, and swap options                                 | Medium   | F002, Recipe API                         |
| F013       | 🔴     | Shareable Lists/Plans            | Allow sharing of grocery lists or meal plans with household members                            | Low      | F003, Supabase Realtime                  |
| F014       | 🔴     | Desktop App Support              | Package app for desktop platforms using Flutter                                                | Low      | Flutter Desktop Export                   |
| F015       | 🔴     | Offline Mode Support             | Cache data for offline access to recipes, lists, and budgets                                   | High     | Local Storage                            |
| F016       | 🟢     | Dark Mode Support                | Implement theme switching with dark mode option                                                | Medium   | None                                     |

---

## Technical Requirements

| Req ID | Title                      | Description                                                                       | Priority |
| ------ | -------------------------- | --------------------------------------------------------------------------------- | -------- |
| T001   | Flutter App (Mobile/Web)   | Cross-platform app for Android, iOS, and Web                                      | High     |
| T002   | Flutter Desktop Support    | Prepare codebase for desktop deployment in later phase                            | Low      |
| T003   | Supabase Backend Services  | Use Supabase for Auth, DB (PostgreSQL), storage, and Realtime                     | High     |
| T004   | Node.js Middleware/API     | Node.js service for heavy logic, optimization, and 3rd-party API handling         | High     |
| T005   | Nutrition & Recipe APIs    | Use Edamam, Spoonacular, or FatSecret for nutritional data and recipes            | High     |
| T006   | Store Price Integration    | Integrate store APIs (e.g. Kroger, Walmart) for product listings and pricing      | High     |
| T007   | Google Maps Integration    | Use Google Maps API to get nearby grocery stores and distance matrix              | High     |
| T008   | Budget Optimization Engine | Implement algorithm for reverse budgeting (e.g. knapsack, linear programming)     | High     |
| T009   | CI/CD Pipeline             | Automate app testing and deployment (e.g. GitHub Actions + Supabase + Flutter CI) | Medium   |
| T010   | Logging & Monitoring       | Add application monitoring tools (e.g. Supabase logs, external dashboards)        | Medium   |
| T011   | API Rate Limit Handling    | Implement retry/cache fallback for API call failures or rate limits               | Medium   |
| T012   | Localization Ready         | Externalize strings to support i18n for future global users                       | Low      |

---

## External API Integrations

| Service     | Purpose                          | URL                                              |
| ----------- | -------------------------------- | ------------------------------------------------ |
| Edamam API  | Nutrition and food data          | https://developer.edamam.com                     |
| Spoonacular | Recipes and meal plans           | https://spoonacular.com/food-api                 |
| FatSecret   | Nutrition database (alternative) | https://platform.fatsecret.com                   |
| Kroger API  | Product pricing and availability | https://developer.kroger.com                     |
| Google Maps | Store locator and distance       | https://developers.google.com/maps/documentation |

---

## Development Milestones

| Phase   | Deliverable Description                                           | Duration (Est.) |
| ------- | ----------------------------------------------------------------- | --------------- |
| Phase 1 | MVP: Profile setup, basic plan, grocery list, simple budget check | 4–6 weeks       |
| Phase 2 | Price comparison, API integrations, alt. suggestions, budgeting   | 6–8 weeks       |
| Phase 3 | Reverse budgeting, ML improvements, desktop build                 | 8–12 weeks      |

---

## Notes

- `.env` files must be used to securely store API keys and Supabase tokens
- Reuse Supabase Edge Functions for logic that doesn’t require custom Node.js compute
- Offline-first mode support for grocery list and meal plan caching
- Use Supabase Realtime for collaborative features (e.g. shared lists)

</file>
<file path="ios/.symlinks/plugins/app_links">
Error reading file: read /Users/varyable/Workspace/mobileapps/foodster/ios/.symlinks/plugins/app_links: is a directory
</file>
<file path="ios/.symlinks/plugins/connectivity_plus">
Error reading file: read /Users/varyable/Workspace/mobileapps/foodster/ios/.symlinks/plugins/connectivity_plus: is a directory
</file>
<file path="ios/.symlinks/plugins/flutter_native_splash">
Error reading file: read /Users/varyable/Workspace/mobileapps/foodster/ios/.symlinks/plugins/flutter_native_splash: is a directory
</file>
<file path="ios/.symlinks/plugins/flutter_secure_storage">
Error reading file: read /Users/varyable/Workspace/mobileapps/foodster/ios/.symlinks/plugins/flutter_secure_storage: is a directory
</file>
<file path="ios/.symlinks/plugins/google_maps_flutter_ios">
Error reading file: read /Users/varyable/Workspace/mobileapps/foodster/ios/.symlinks/plugins/google_maps_flutter_ios: is a directory
</file>
<file path="ios/.symlinks/plugins/path_provider_foundation">
Error reading file: read /Users/varyable/Workspace/mobileapps/foodster/ios/.symlinks/plugins/path_provider_foundation: is a directory
</file>
<file path="ios/.symlinks/plugins/shared_preferences_foundation">
Error reading file: read /Users/varyable/Workspace/mobileapps/foodster/ios/.symlinks/plugins/shared_preferences_foundation: is a directory
</file>
<file path="ios/.symlinks/plugins/sqflite_darwin">
Error reading file: read /Users/varyable/Workspace/mobileapps/foodster/ios/.symlinks/plugins/sqflite_darwin: is a directory
</file>
<file path="ios/.symlinks/plugins/url_launcher_ios">
Error reading file: read /Users/varyable/Workspace/mobileapps/foodster/ios/.symlinks/plugins/url_launcher_ios: is a directory
</file>
<file path="ios/Flutter/ephemeral/flutter_lldb_helper.py">
#
# Generated file, do not edit.
#

import lldb

def handle_new_rx_page(frame: lldb.SBFrame, bp_loc, extra_args, intern_dict):
    """Intercept NOTIFY_DEBUGGER_ABOUT_RX_PAGES and touch the pages."""
    base = frame.register["x0"].GetValueAsAddress()
    page_len = frame.register["x1"].GetValueAsUnsigned()

    # Note: NOTIFY_DEBUGGER_ABOUT_RX_PAGES will check contents of the
    # first page to see if handled it correctly. This makes diagnosing
    # misconfiguration (e.g. missing breakpoint) easier.
    data = bytearray(page_len)
    data[0:8] = b'IHELPED!'

    error = lldb.SBError()
    frame.GetThread().GetProcess().WriteMemory(base, data, error)
    if not error.Success():
        print(f'Failed to write into {base}[+{page_len}]', error)
        return

def __lldb_init_module(debugger: lldb.SBDebugger, _):
    target = debugger.GetDummyTarget()
    # Caveat: must use BreakpointCreateByRegEx here and not
    # BreakpointCreateByName. For some reasons callback function does not
    # get carried over from dummy target for the later.
    bp = target.BreakpointCreateByRegex("^NOTIFY_DEBUGGER_ABOUT_RX_PAGES$")
    bp.SetScriptCallbackFunction('{}.handle_new_rx_page'.format(__name__))
    bp.SetAutoContinue(True)
    print("-- LLDB integration loaded --")

</file>
<file path="ios/Flutter/ephemeral/flutter_lldbinit">
#
# Generated file, do not edit.
#

command script import --relative-to-command-file flutter_lldb_helper.py

</file>
<file path="ios/Flutter/AppFrameworkInfo.plist">
<?xml version="1.0" encoding="UTF-8"?>
<!DOCTYPE plist PUBLIC "-//Apple//DTD PLIST 1.0//EN" "http://www.apple.com/DTDs/PropertyList-1.0.dtd">
<plist version="1.0">
<dict>
  <key>CFBundleDevelopmentRegion</key>
  <string>en</string>
  <key>CFBundleExecutable</key>
  <string>App</string>
  <key>CFBundleIdentifier</key>
  <string>io.flutter.flutter.app</string>
  <key>CFBundleInfoDictionaryVersion</key>
  <string>6.0</string>
  <key>CFBundleName</key>
  <string>App</string>
  <key>CFBundlePackageType</key>
  <string>FMWK</string>
  <key>CFBundleShortVersionString</key>
  <string>1.0</string>
  <key>CFBundleSignature</key>
  <string>????</string>
  <key>CFBundleVersion</key>
  <string>1.0</string>
  <key>MinimumOSVersion</key>
  <string>12.0</string>
</dict>
</plist>

</file>
<file path="ios/Flutter/Debug.xcconfig">
#include? "Pods/Target Support Files/Pods-Runner/Pods-Runner.debug.xcconfig"
#include "Generated.xcconfig"

</file>
<file path="ios/Flutter/Flutter.podspec">
#
# This podspec is NOT to be published. It is only used as a local source!
# This is a generated file; do not edit or check into version control.
#

Pod::Spec.new do |s|
  s.name             = 'Flutter'
  s.version          = '1.0.0'
  s.summary          = 'A UI toolkit for beautiful and fast apps.'
  s.homepage         = 'https://flutter.dev'
  s.license          = { :type => 'BSD' }
  s.author           = { 'Flutter Dev Team' => 'flutter-dev@googlegroups.com' }
  s.source           = { :git => 'https://github.com/flutter/engine', :tag => s.version.to_s }
  s.ios.deployment_target = '12.0'
  # Framework linking is handled by Flutter tooling, not CocoaPods.
  # Add a placeholder to satisfy `s.dependency 'Flutter'` plugin podspecs.
  s.vendored_frameworks = 'path/to/nothing'
end

</file>
<file path="ios/Flutter/flutter_export_environment.sh">
#!/bin/sh
# This is a generated file; do not edit or check into version control.
export "FLUTTER_ROOT=/Users/varyable/Workspace/flutter"
export "FLUTTER_APPLICATION_PATH=/Users/varyable/Workspace/mobileapps/foodster"
export "COCOAPODS_PARALLEL_CODE_SIGN=true"
export "FLUTTER_TARGET=/Users/varyable/Workspace/mobileapps/foodster/lib/main.dart"
export "FLUTTER_BUILD_DIR=build"
export "FLUTTER_BUILD_NAME=1.0.0"
export "FLUTTER_BUILD_NUMBER=1"
export "DART_DEFINES=VVNFX01DUF9TRVJWRVI9dHJ1ZQ==,RkxVVFRFUl9WRVJTSU9OPTMuMzIuNA==,RkxVVFRFUl9DSEFOTkVMPXN0YWJsZQ==,RkxVVFRFUl9HSVRfVVJMPWh0dHBzOi8vZ2l0aHViLmNvbS9mbHV0dGVyL2ZsdXR0ZXIuZ2l0,RkxVVFRFUl9GUkFNRVdPUktfUkVWSVNJT049NmZiYTI0NDdlOQ==,RkxVVFRFUl9FTkdJTkVfUkVWSVNJT049OGNkMTllNTA5ZA==,RkxVVFRFUl9EQVJUX1ZFUlNJT049My44LjE="
export "DART_OBFUSCATION=false"
export "TRACK_WIDGET_CREATION=true"
export "TREE_SHAKE_ICONS=false"
export "PACKAGE_CONFIG=/Users/varyable/Workspace/mobileapps/foodster/.dart_tool/package_config.json"

</file>
<file path="ios/Flutter/Generated.xcconfig">
// This is a generated file; do not edit or check into version control.
FLUTTER_ROOT=/Users/varyable/Workspace/flutter
FLUTTER_APPLICATION_PATH=/Users/varyable/Workspace/mobileapps/foodster
COCOAPODS_PARALLEL_CODE_SIGN=true
FLUTTER_TARGET=/Users/varyable/Workspace/mobileapps/foodster/lib/main.dart
FLUTTER_BUILD_DIR=build
FLUTTER_BUILD_NAME=1.0.0
FLUTTER_BUILD_NUMBER=1
EXCLUDED_ARCHS[sdk=iphonesimulator*]=i386
EXCLUDED_ARCHS[sdk=iphoneos*]=armv7
DART_DEFINES=VVNFX01DUF9TRVJWRVI9dHJ1ZQ==,RkxVVFRFUl9WRVJTSU9OPTMuMzIuNA==,RkxVVFRFUl9DSEFOTkVMPXN0YWJsZQ==,RkxVVFRFUl9HSVRfVVJMPWh0dHBzOi8vZ2l0aHViLmNvbS9mbHV0dGVyL2ZsdXR0ZXIuZ2l0,RkxVVFRFUl9GUkFNRVdPUktfUkVWSVNJT049NmZiYTI0NDdlOQ==,RkxVVFRFUl9FTkdJTkVfUkVWSVNJT049OGNkMTllNTA5ZA==,RkxVVFRFUl9EQVJUX1ZFUlNJT049My44LjE=
DART_OBFUSCATION=false
TRACK_WIDGET_CREATION=true
TREE_SHAKE_ICONS=false
PACKAGE_CONFIG=/Users/varyable/Workspace/mobileapps/foodster/.dart_tool/package_config.json

</file>
<file path="ios/Flutter/Release.xcconfig">
#include? "Pods/Target Support Files/Pods-Runner/Pods-Runner.release.xcconfig"
#include "Generated.xcconfig"

</file>
<file path="ios/Runner/Assets.xcassets/AppIcon.appiconset/Contents.json">
{
  "images" : [
    {
      "size" : "20x20",
      "idiom" : "iphone",
      "filename" : "Icon-App-20x20@2x.png",
      "scale" : "2x"
    },
    {
      "size" : "20x20",
      "idiom" : "iphone",
      "filename" : "Icon-App-20x20@3x.png",
      "scale" : "3x"
    },
    {
      "size" : "29x29",
      "idiom" : "iphone",
      "filename" : "Icon-App-29x29@1x.png",
      "scale" : "1x"
    },
    {
      "size" : "29x29",
      "idiom" : "iphone",
      "filename" : "Icon-App-29x29@2x.png",
      "scale" : "2x"
    },
    {
      "size" : "29x29",
      "idiom" : "iphone",
      "filename" : "Icon-App-29x29@3x.png",
      "scale" : "3x"
    },
    {
      "size" : "40x40",
      "idiom" : "iphone",
      "filename" : "Icon-App-40x40@2x.png",
      "scale" : "2x"
    },
    {
      "size" : "40x40",
      "idiom" : "iphone",
      "filename" : "Icon-App-40x40@3x.png",
      "scale" : "3x"
    },
    {
      "size" : "60x60",
      "idiom" : "iphone",
      "filename" : "Icon-App-60x60@2x.png",
      "scale" : "2x"
    },
    {
      "size" : "60x60",
      "idiom" : "iphone",
      "filename" : "Icon-App-60x60@3x.png",
      "scale" : "3x"
    },
    {
      "size" : "20x20",
      "idiom" : "ipad",
      "filename" : "Icon-App-20x20@1x.png",
      "scale" : "1x"
    },
    {
      "size" : "20x20",
      "idiom" : "ipad",
      "filename" : "Icon-App-20x20@2x.png",
      "scale" : "2x"
    },
    {
      "size" : "29x29",
      "idiom" : "ipad",
      "filename" : "Icon-App-29x29@1x.png",
      "scale" : "1x"
    },
    {
      "size" : "29x29",
      "idiom" : "ipad",
      "filename" : "Icon-App-29x29@2x.png",
      "scale" : "2x"
    },
    {
      "size" : "40x40",
      "idiom" : "ipad",
      "filename" : "Icon-App-40x40@1x.png",
      "scale" : "1x"
    },
    {
      "size" : "40x40",
      "idiom" : "ipad",
      "filename" : "Icon-App-40x40@2x.png",
      "scale" : "2x"
    },
    {
      "size" : "76x76",
      "idiom" : "ipad",
      "filename" : "Icon-App-76x76@1x.png",
      "scale" : "1x"
    },
    {
      "size" : "76x76",
      "idiom" : "ipad",
      "filename" : "Icon-App-76x76@2x.png",
      "scale" : "2x"
    },
    {
      "size" : "83.5x83.5",
      "idiom" : "ipad",
      "filename" : "Icon-App-83.5x83.5@2x.png",
      "scale" : "2x"
    },
    {
      "size" : "1024x1024",
      "idiom" : "ios-marketing",
      "filename" : "Icon-App-1024x1024@1x.png",
      "scale" : "1x"
    }
  ],
  "info" : {
    "version" : 1,
    "author" : "xcode"
  }
}

</file>
<file path="ios/Runner/Assets.xcassets/LaunchImage.imageset/Contents.json">
{
  "images" : [
    {
      "idiom" : "universal",
      "filename" : "LaunchImage.png",
      "scale" : "1x"
    },
    {
      "idiom" : "universal",
      "filename" : "LaunchImage@2x.png",
      "scale" : "2x"
    },
    {
      "idiom" : "universal",
      "filename" : "LaunchImage@3x.png",
      "scale" : "3x"
    }
  ],
  "info" : {
    "version" : 1,
    "author" : "xcode"
  }
}

</file>
<file path="ios/Runner/Assets.xcassets/LaunchImage.imageset/README.md">
# Launch Screen Assets

You can customize the launch screen with your own desired assets by replacing the image files in this directory.

You can also do it by opening your Flutter project's Xcode project with `open ios/Runner.xcworkspace`, selecting `Runner/Assets.xcassets` in the Project Navigator and dropping in the desired images.
</file>
<file path="ios/Runner/Base.lproj/LaunchScreen.storyboard">
<?xml version="1.0" encoding="UTF-8" standalone="no"?>
<document type="com.apple.InterfaceBuilder3.CocoaTouch.Storyboard.XIB" version="3.0" toolsVersion="12121" systemVersion="16G29" targetRuntime="iOS.CocoaTouch" propertyAccessControl="none" useAutolayout="YES" launchScreen="YES" colorMatched="YES" initialViewController="01J-lp-oVM">
    <dependencies>
        <deployment identifier="iOS"/>
        <plugIn identifier="com.apple.InterfaceBuilder.IBCocoaTouchPlugin" version="12089"/>
    </dependencies>
    <scenes>
        <!--View Controller-->
        <scene sceneID="EHf-IW-A2E">
            <objects>
                <viewController id="01J-lp-oVM" sceneMemberID="viewController">
                    <layoutGuides>
                        <viewControllerLayoutGuide type="top" id="Ydg-fD-yQy"/>
                        <viewControllerLayoutGuide type="bottom" id="xbc-2k-c8Z"/>
                    </layoutGuides>
                    <view key="view" contentMode="scaleToFill" id="Ze5-6b-2t3">
                        <autoresizingMask key="autoresizingMask" widthSizable="YES" heightSizable="YES"/>
                        <subviews>
                            <imageView opaque="NO" clipsSubviews="YES" multipleTouchEnabled="YES" contentMode="center" image="LaunchImage" translatesAutoresizingMaskIntoConstraints="NO" id="YRO-k0-Ey4">
                            </imageView>
                        </subviews>
                        <color key="backgroundColor" red="1" green="1" blue="1" alpha="1" colorSpace="custom" customColorSpace="sRGB"/>
                        <constraints>
                            <constraint firstItem="YRO-k0-Ey4" firstAttribute="centerX" secondItem="Ze5-6b-2t3" secondAttribute="centerX" id="1a2-6s-vTC"/>
                            <constraint firstItem="YRO-k0-Ey4" firstAttribute="centerY" secondItem="Ze5-6b-2t3" secondAttribute="centerY" id="4X2-HB-R7a"/>
                        </constraints>
                    </view>
                </viewController>
                <placeholder placeholderIdentifier="IBFirstResponder" id="iYj-Kq-Ea1" userLabel="First Responder" sceneMemberID="firstResponder"/>
            </objects>
            <point key="canvasLocation" x="53" y="375"/>
        </scene>
    </scenes>
    <resources>
        <image name="LaunchImage" width="168" height="185"/>
    </resources>
</document>

</file>
<file path="ios/Runner/Base.lproj/Main.storyboard">
<?xml version="1.0" encoding="UTF-8" standalone="no"?>
<document type="com.apple.InterfaceBuilder3.CocoaTouch.Storyboard.XIB" version="3.0" toolsVersion="10117" systemVersion="15F34" targetRuntime="iOS.CocoaTouch" propertyAccessControl="none" useAutolayout="YES" useTraitCollections="YES" initialViewController="BYZ-38-t0r">
    <dependencies>
        <deployment identifier="iOS"/>
        <plugIn identifier="com.apple.InterfaceBuilder.IBCocoaTouchPlugin" version="10085"/>
    </dependencies>
    <scenes>
        <!--Flutter View Controller-->
        <scene sceneID="tne-QT-ifu">
            <objects>
                <viewController id="BYZ-38-t0r" customClass="FlutterViewController" sceneMemberID="viewController">
                    <layoutGuides>
                        <viewControllerLayoutGuide type="top" id="y3c-jy-aDJ"/>
                        <viewControllerLayoutGuide type="bottom" id="wfy-db-euE"/>
                    </layoutGuides>
                    <view key="view" contentMode="scaleToFill" id="8bC-Xf-vdC">
                        <rect key="frame" x="0.0" y="0.0" width="600" height="600"/>
                        <autoresizingMask key="autoresizingMask" widthSizable="YES" heightSizable="YES"/>
                        <color key="backgroundColor" white="1" alpha="1" colorSpace="custom" customColorSpace="calibratedWhite"/>
                    </view>
                </viewController>
                <placeholder placeholderIdentifier="IBFirstResponder" id="dkx-z0-nzr" sceneMemberID="firstResponder"/>
            </objects>
        </scene>
    </scenes>
</document>

</file>
<file path="ios/Runner/AppDelegate.swift">
import Flutter
import UIKit

@main
@objc class AppDelegate: FlutterAppDelegate {
  override func application(
    _ application: UIApplication,
    didFinishLaunchingWithOptions launchOptions: [UIApplication.LaunchOptionsKey: Any]?
  ) -> Bool {
    GeneratedPluginRegistrant.register(with: self)
    return super.application(application, didFinishLaunchingWithOptions: launchOptions)
  }
}

</file>
<file path="ios/Runner/GeneratedPluginRegistrant.h">
//
//  Generated file. Do not edit.
//

// clang-format off

#ifndef GeneratedPluginRegistrant_h
#define GeneratedPluginRegistrant_h

#import <Flutter/Flutter.h>

NS_ASSUME_NONNULL_BEGIN

@interface GeneratedPluginRegistrant : NSObject
+ (void)registerWithRegistry:(NSObject<FlutterPluginRegistry>*)registry;
@end

NS_ASSUME_NONNULL_END
#endif /* GeneratedPluginRegistrant_h */

</file>
<file path="ios/Runner/GeneratedPluginRegistrant.m">
//
//  Generated file. Do not edit.
//

// clang-format off

#import "GeneratedPluginRegistrant.h"

#if __has_include(<app_links/AppLinksIosPlugin.h>)
#import <app_links/AppLinksIosPlugin.h>
#else
@import app_links;
#endif

#if __has_include(<connectivity_plus/ConnectivityPlusPlugin.h>)
#import <connectivity_plus/ConnectivityPlusPlugin.h>
#else
@import connectivity_plus;
#endif

#if __has_include(<flutter_native_splash/FlutterNativeSplashPlugin.h>)
#import <flutter_native_splash/FlutterNativeSplashPlugin.h>
#else
@import flutter_native_splash;
#endif

#if __has_include(<flutter_secure_storage/FlutterSecureStoragePlugin.h>)
#import <flutter_secure_storage/FlutterSecureStoragePlugin.h>
#else
@import flutter_secure_storage;
#endif

#if __has_include(<google_maps_flutter_ios/FLTGoogleMapsPlugin.h>)
#import <google_maps_flutter_ios/FLTGoogleMapsPlugin.h>
#else
@import google_maps_flutter_ios;
#endif

#if __has_include(<path_provider_foundation/PathProviderPlugin.h>)
#import <path_provider_foundation/PathProviderPlugin.h>
#else
@import path_provider_foundation;
#endif

#if __has_include(<shared_preferences_foundation/SharedPreferencesPlugin.h>)
#import <shared_preferences_foundation/SharedPreferencesPlugin.h>
#else
@import shared_preferences_foundation;
#endif

#if __has_include(<sqflite_darwin/SqflitePlugin.h>)
#import <sqflite_darwin/SqflitePlugin.h>
#else
@import sqflite_darwin;
#endif

#if __has_include(<url_launcher_ios/URLLauncherPlugin.h>)
#import <url_launcher_ios/URLLauncherPlugin.h>
#else
@import url_launcher_ios;
#endif

@implementation GeneratedPluginRegistrant

+ (void)registerWithRegistry:(NSObject<FlutterPluginRegistry>*)registry {
  [AppLinksIosPlugin registerWithRegistrar:[registry registrarForPlugin:@"AppLinksIosPlugin"]];
  [ConnectivityPlusPlugin registerWithRegistrar:[registry registrarForPlugin:@"ConnectivityPlusPlugin"]];
  [FlutterNativeSplashPlugin registerWithRegistrar:[registry registrarForPlugin:@"FlutterNativeSplashPlugin"]];
  [FlutterSecureStoragePlugin registerWithRegistrar:[registry registrarForPlugin:@"FlutterSecureStoragePlugin"]];
  [FLTGoogleMapsPlugin registerWithRegistrar:[registry registrarForPlugin:@"FLTGoogleMapsPlugin"]];
  [PathProviderPlugin registerWithRegistrar:[registry registrarForPlugin:@"PathProviderPlugin"]];
  [SharedPreferencesPlugin registerWithRegistrar:[registry registrarForPlugin:@"SharedPreferencesPlugin"]];
  [SqflitePlugin registerWithRegistrar:[registry registrarForPlugin:@"SqflitePlugin"]];
  [URLLauncherPlugin registerWithRegistrar:[registry registrarForPlugin:@"URLLauncherPlugin"]];
}

@end

</file>
<file path="ios/Runner/Info.plist">
<?xml version="1.0" encoding="UTF-8"?>
<!DOCTYPE plist PUBLIC "-//Apple//DTD PLIST 1.0//EN" "http://www.apple.com/DTDs/PropertyList-1.0.dtd">
<plist version="1.0">
<dict>
	<key>CADisableMinimumFrameDurationOnPhone</key>
	<true/>
	<key>CFBundleDevelopmentRegion</key>
	<string>$(DEVELOPMENT_LANGUAGE)</string>
	<key>CFBundleDisplayName</key>
	<string>Foodster</string>
	<key>CFBundleExecutable</key>
	<string>$(EXECUTABLE_NAME)</string>
	<key>CFBundleIdentifier</key>
	<string>$(PRODUCT_BUNDLE_IDENTIFIER)</string>
	<key>CFBundleInfoDictionaryVersion</key>
	<string>6.0</string>
	<key>CFBundleName</key>
	<string>foodster</string>
	<key>CFBundlePackageType</key>
	<string>APPL</string>
	<key>CFBundleShortVersionString</key>
	<string>$(FLUTTER_BUILD_NAME)</string>
	<key>CFBundleSignature</key>
	<string>????</string>
	<key>CFBundleVersion</key>
	<string>$(FLUTTER_BUILD_NUMBER)</string>
	<key>LSRequiresIPhoneOS</key>
	<true/>
	<key>UIApplicationSupportsIndirectInputEvents</key>
	<true/>
	<key>UILaunchStoryboardName</key>
	<string>LaunchScreen</string>
	<key>UIMainStoryboardFile</key>
	<string>Main</string>
	<key>UISupportedInterfaceOrientations</key>
	<array>
		<string>UIInterfaceOrientationPortrait</string>
		<string>UIInterfaceOrientationLandscapeLeft</string>
		<string>UIInterfaceOrientationLandscapeRight</string>
	</array>
	<key>UISupportedInterfaceOrientations~ipad</key>
	<array>
		<string>UIInterfaceOrientationPortrait</string>
		<string>UIInterfaceOrientationPortraitUpsideDown</string>
		<string>UIInterfaceOrientationLandscapeLeft</string>
		<string>UIInterfaceOrientationLandscapeRight</string>
	</array>
</dict>
</plist>

</file>
<file path="ios/Runner/Runner-Bridging-Header.h">
#import "GeneratedPluginRegistrant.h"

</file>
<file path="ios/Runner.xcodeproj/project.xcworkspace/xcshareddata/IDEWorkspaceChecks.plist">
<?xml version="1.0" encoding="UTF-8"?>
<!DOCTYPE plist PUBLIC "-//Apple//DTD PLIST 1.0//EN" "http://www.apple.com/DTDs/PropertyList-1.0.dtd">
<plist version="1.0">
<dict>
	<key>IDEDidComputeMac32BitWarning</key>
	<true/>
</dict>
</plist>

</file>
<file path="ios/Runner.xcodeproj/project.xcworkspace/xcshareddata/WorkspaceSettings.xcsettings">
<?xml version="1.0" encoding="UTF-8"?>
<!DOCTYPE plist PUBLIC "-//Apple//DTD PLIST 1.0//EN" "http://www.apple.com/DTDs/PropertyList-1.0.dtd">
<plist version="1.0">
<dict>
	<key>PreviewsEnabled</key>
	<false/>
</dict>
</plist>

</file>
<file path="ios/Runner.xcodeproj/project.xcworkspace/xcuserdata/varyable.xcuserdatad/UserInterfaceState.xcuserstate">
bplist00�        
X$versionY$archiverT$topX$objects ��_NSKeyedArchiver�  	UState���      9 : ; < = > ? @ A B C D E F G K Q R X [ a b h t u v w x y } � � � � � � � � � � � � � � � � � � � � � � � � � � � � � � � � � � � � � � � !"#&:;<=>?@ABCYZ[\]^_`ablmno{|}~������������������������������������������ 
#$%&'()*4567;?CKOST`aekoptx|}���������������������������������U$null� 
     WNS.keysZNS.objectsV$class�  ���  ��Z�1_IDEWorkspaceDocument_$4E033D8C-ADF1-48DF-86E5-9D42B4886F35� 
    ) 8�        ! " # $ % & ' (�����	�
���
������ * + , - . / 0 / 2 3 4 - 6 7���.�2�4�5�6�5�7�:�I�2�Y�S�"_RecentEditorDocumentURLs_DefaultEditorStatesForURLs\ActiveScheme_ActiveProjectSetIdentifierKey_$RunDestinationArchitectureVisibility_%forceBuildForAllArchitecturesIfNeeded_DocumentWindows_EnableThreadGallery_WindowArrangementDebugInfo_RunContextRecents_ActiveRunDestination_ActiveProjectSetNameKey_SelectedWindows_BreakpointsActivated�   H J� I��� L  M N O PWNS.base[NS.relative� ��_Ifile:///Users/varyable/Workspace/mobileapps/foodster/ios/Runner.xcodeproj� S T U VZ$classnameX$classesUNSURL� U WXNSObject� S T Y ZWNSArray� Y W� 
   \ ^ 8� ]�� _��"_7Xcode.Xcode3ProjectSupport.EditorDocument.Xcode3Project� 
   c e 8� I�� f��"� 
   i n 8� j k l m����� o p q r� �!�#�-�"_,Xcode3ProjectEditorPreviousTargetEditorClass_'Xcode3ProjectEditor_Xcode3SigningEditor_,Xcode3ProjectEditorSelectedDocumentLocations_&Xcode3ProjectEditor_Xcode3TargetEditor_Xcode3SigningEditor� 
   z { 8���"� S T ~ _NSMutableDictionary� ~ � W\NSDictionary�   � J� ��$�� � � �  � � � �YselectionYtimestamp[documentURL�'�&�%�,_Ifile:///Users/varyable/Workspace/mobileapps/foodster/ios/Runner.xcodeproj#A�q"Jq�� 
   � � 8� � ��(�)� � ��*�+�"VEditorVTarget_Xcode3SigningEditorVRunner� S T � �_Xcode3ProjectDocumentLocation� � � W_Xcode3ProjectDocumentLocation_DVTDocumentLocation� 
   � � 8���"� 
   � � � ��/� ��0�1]IDENameStringVRunner� S T � �� � W�  ��3� S T � �VNSNull� � W �   � J� ��� 
   � � 8� �� ��8�"�   � ¡ ��9� S T � �^NSMutableArray� � Y W� 
   � � � � � ʀ;�<�=� � � ΀>�A�D�1_IDERunContextRecentsSchemesKey_5IDERunContextRecentsLastUsedRunDestinationBySchemeKey_&IDERunContextRecentsRunDestinationsKey� 
   � � 8� ��+� ׀?�"� �  � �WNS.time#A�r{�ۀ@� S T � �VNSDate� � W� 
   � � 8� ��+� ��B�"�  � � �YNS.string�C_(00008110-00027DC83A87801E_iphoneos_arm64� S T � �_NSMutableString� � � WXNSString� 
   � � 8� � ��E�F� � ��G�H�"_:3A5CEE0D-1CBF-4EDE-8D98-AAF337FB3FA3_iphonesimulator_arm64_(00008110-00027DC83A87801E_iphoneos_arm64� �  � �#A�q$RO��@� �  � �#A�r��"�@� 
   �	 � �J�K�L�M�N�O�P�Q�R� 7 / 7�S�T�5�T�U�V�W�X�S�1ZisEligible_targetDevicePlatform_targetDeviceIsWireless_targetSDKVariant_targetDeviceLocation_targetArchitectureYtargetSDK_targetDeviceModelCode_targetDeviceIsConcrete	Xiphoneos_2DVTCoreDevice-CDB7EF06-122D-53F5-876F-D5B32C3619C7Uarm64\iphoneos18.5ZiPhone14,3�  $  �9� 
  '0 8�()*+,-./�[�\�]�^�_�`�a�b� 7 /34 /+ 7 /�S�5�c�d�5�^�S�5�"_-IDEHasMigratedValuesFromNSRestorableStateData_IDEWindowIsFullScreen^IDEWindowFrame_>IDEWorkspaceTabController_1D35B8C0-ACE7-4DEC-9D7A-8F3B4F5FED43_&IDEWindowTabBarWasVisibleWithSingleTab_IDEActiveWorkspaceTabController_IDEWindowToolbarIsVisible_IDEWindowTabBarIsVisible_ 1220 618 1400 900 0 0 3840 2135 � 
  DN 8�EFGHIJKLM�e�f�g�h�i�j�k�l�m� 7PQRSTU /W�S�n�����������5���"_IDEShowNavigator_IDENavigatorArea_IDEUtilitiesWidth_IDEInspectorArea_IDENavigatorWidth\ViewDebugger_MemoryGraphDebugger_IDEShowUtilities]IDEEditorArea� 
  cg 8�def�o�p�q�hij�r�~��"_ Xcode.IDEKit.Navigator.Workspace_SelectedNavigator_GroupSelections� 
  pu 8�qrst�s�t�u�v�vwxv�w�x�y�w�"_FilterStateByModeKey_LastNavigatorMode_UnfilterStateByModeKey_FilteredUIStateByModeKey� 
  �� ���1_IDENavigatorModeSolitary� 
  �� ���z���{�1_IDENavigatorModeSolitary� ���_codablePlistRepresentation�}�|O�bplist00�&'_lastAccessedDateYitemState]selectedItems^scrollPosition3A�r����
 "$�	
TpathYindexHint�VRunner�^expansionState3A�r{)�� �	�[RunnerTests�3A�r{)���	�TPods�3A�r{)�a�	�WFlutter�!3A�r{)���	#��%3A�r{#����(+�)*#        #�$      �,-#@q      #@�(        $ . < K T _ d i s v }  � � � � � � � � � � � � � � � � � � � � �	"+.7             .              @� S T��_&ExplorableOutlineViewArchivableUIState��� W_&ExplorableOutlineViewArchivableUIState_b_TtGCV16DVTExplorableKit26ExplorableOutlineViewTypes7UIState_VS_31ExplorableStateSavingIdentifier__ Xcode.IDEKit.Navigator.Workspace� 
  �� 8�����i�~�"_%Xcode.IDEKit.NavigatorGroup.Structure#@p@     � 
  �� 8���������������"_'userPreferredInspectorGroupExtensionIDs_!userPreferredCategoryExtensionIDs�  � J���  �  �9#@q      � 
  �� 8����� 7�S�"_ShowsOnlyVisibleViewObjects� 
  �� 8�������� / /�5�5�"_ShowsOnlyLeakedBlocks_XRShowsOnlyContentFromWorkspace� 
  �� 8�����������̀����������������������� .��������؀��4�����������Ȁ������"^MaximizedState_*BeforeComparisonMode_UserVisibleEditorMode_NavigationStyleZEditorMode_DebuggerSplitView_EditorAreaSplitStates_#primaryEditorArchivedRepresentation_IDEDefaultDebugArea_ EditorMultipleSplitPrimaryLayout_ SelectedEditorAreaSplitIndexPath_ DefaultPersistentRepresentations ZOpenInTabs � 
  �� 8�ꀝ�쀞�"_DVTSplitViewItems�  � ¢�聼0��9� 
  �� ������������ 7����S���1]DVTIdentifier\DVTIsVisible_DVTViewMagnitudeYIDEEditor#@��     � 
   �����������	 7���S���1_IDEDebuggerArea#@\�     �   ¡���9� 
   8��������������  /�����������5�"ZEditorMode_EditorTabBarState_EditorHistoryStacks]EditorMode13+[ItemKindKey_ShouldShowPullRequestComments � 
  +/ 8�,-.�������0 . .���4�4�"_TabsAsHistoryItems_SelectedTabIndex_DynamicTabIndex�  8 J� -�2��  < ¡=���9�@ AB_currentEditorHistoryItem�����DEF  N N NJ_navigableItemName_stateDictionary_documentNavigableItemName� � � ��� S TLM_IDEEditorHistoryItem�N W_IDEEditorHistoryItem� S TPQ_IDEEditorHistoryStack�R W_IDEEditorHistoryStack_ItemKind_Editor�UVW XYZ[\]^__DocumentLocation^IdentifierPath_WorkspaceRootFilePath_DomainIdentifier_IndexOfDocumentIdentifier���Āǀ���_/Xcode.IDENavigableItemDomain.WorkspaceStructure�  b J�c����fg  � .jZIdentifierUIndex�+�4��� S Tlm_IDEArchivableStringIndexPair�n W_IDEArchivableStringIndexPair�������� �  � �r N�%�À � S Tuv_DVTDocumentLocation�w W_DVTDocumentLocation� yz{ZpathString�ƀ�_B/Users/varyable/Workspace/mobileapps/foodster/ios/Runner.xcodeproj� S T~[DVTFilePath��� W[DVTFilePath_PackedPathEntry� S T��_(IDENavigableItemArchivableRepresentation�� W_(IDENavigableItemArchivableRepresentation� 
  �� 8������ɀʀˀ̤�����̀Ҁڀۀ"XLeftView_IDESplitViewDebugAreaZLayoutModeYRightView� 
  �� 8������΀πЀѤ / .� 7�5�4���S�"_VariablesViewShowsRawValues_VariablesViewSelectedScope_ VariablesViewViewSortDescriptors_VariablesViewShowsType� 
  �� 8���ӡ��Ԁ"_DVTSplitViewItems�  � ¢���Հ؀9� 
  �� ������������ 7��րS�׀1XLeftView#@��     � 
  �� ������������ 7��ـS�׀1YRightView� 
  �� 8��������Ӏ܀݀ހ߀�������� / / / 7 / / / /�5�5�5�S�5�5�5�5�"_+IDEStructuredConsoleAreaLibraryEnabledState_-IDEStructuredConsoleAreaTimestampEnabledState_*IDEStructuredConsoleAreaPIDTIDEnabledState_,IDEStructuredConsoleAreaMetadataEnabledState_(IDEStructuredConsoleAreaTypeEnabledState_-IDEStructuredConsoleAreaSubsystemEnabledState_/IDEStructuredConsoleAreaProcessNameEnabledState_,IDEStructuredConsoleAreaCategoryEnabledState_Layout_LeftToRight��� � ��_NSIndexPathLength_NSIndexPathValue��� S T��[NSIndexPath�� W[NSIndexPath� 
  �� 8���"   " , 1 : ? Q V \ ^17DLW^ceglnpr������������������!#%')+-/1Liv����
*>Uo������������$/8>CLU]bortwy{�������������������.X����������
+5?KMOQS���������������$DZghikx{}����������������������������				+	2	4	6	8	?	A	C	E	G	h	�	�	�	�	�	�	�	�	�	�	�	�



 
#
%
(
*
,
5
?
A
l
u
�
�
�
�
�
�
�
�
�
�
�
� )24=FHUhjlnprtvxz���������������%=VW`������������������
 








@
X
g
�
�
�*MZmoqsuwy{}���������������*=KX_acelnprt���������������2MZ[\^y����������������^�����������������:CDFOPR[hkmprt������������!#%'>@BDFHJLNPRTVe�����:]��������������������������'1:GNPRT[]_acu~����������������������>@MTVXZacegi~���������������">@BDFOfk��������
%8TVXZ\^`��������������(*,.7MRhq|~�������38cpy{}�����������������������6Yr������������������������!#0ACEGIKMOQbdfhjlnprt���.Y����    3 5 > J O [ h i j            �               l
</file>
<file path="ios/Runner.xcodeproj/project.xcworkspace/contents.xcworkspacedata">
<?xml version="1.0" encoding="UTF-8"?>
<Workspace
   version = "1.0">
   <FileRef
      location = "self:">
   </FileRef>
</Workspace>

</file>
<file path="ios/Runner.xcodeproj/xcshareddata/xcschemes/Runner.xcscheme">
<?xml version="1.0" encoding="UTF-8"?>
<Scheme
   LastUpgradeVersion = "1510"
   version = "1.3">
   <BuildAction
      parallelizeBuildables = "YES"
      buildImplicitDependencies = "YES">
      <BuildActionEntries>
         <BuildActionEntry
            buildForTesting = "YES"
            buildForRunning = "YES"
            buildForProfiling = "YES"
            buildForArchiving = "YES"
            buildForAnalyzing = "YES">
            <BuildableReference
               BuildableIdentifier = "primary"
               BlueprintIdentifier = "97C146ED1CF9000F007C117D"
               BuildableName = "Runner.app"
               BlueprintName = "Runner"
               ReferencedContainer = "container:Runner.xcodeproj">
            </BuildableReference>
         </BuildActionEntry>
      </BuildActionEntries>
   </BuildAction>
   <TestAction
      buildConfiguration = "Debug"
      selectedDebuggerIdentifier = "Xcode.DebuggerFoundation.Debugger.LLDB"
      selectedLauncherIdentifier = "Xcode.DebuggerFoundation.Launcher.LLDB"
      customLLDBInitFile = "$(SRCROOT)/Flutter/ephemeral/flutter_lldbinit"
      shouldUseLaunchSchemeArgsEnv = "YES">
      <MacroExpansion>
         <BuildableReference
            BuildableIdentifier = "primary"
            BlueprintIdentifier = "97C146ED1CF9000F007C117D"
            BuildableName = "Runner.app"
            BlueprintName = "Runner"
            ReferencedContainer = "container:Runner.xcodeproj">
         </BuildableReference>
      </MacroExpansion>
      <Testables>
         <TestableReference
            skipped = "NO"
            parallelizable = "YES">
            <BuildableReference
               BuildableIdentifier = "primary"
               BlueprintIdentifier = "331C8080294A63A400263BE5"
               BuildableName = "RunnerTests.xctest"
               BlueprintName = "RunnerTests"
               ReferencedContainer = "container:Runner.xcodeproj">
            </BuildableReference>
         </TestableReference>
      </Testables>
   </TestAction>
   <LaunchAction
      buildConfiguration = "Debug"
      selectedDebuggerIdentifier = "Xcode.DebuggerFoundation.Debugger.LLDB"
      selectedLauncherIdentifier = "Xcode.DebuggerFoundation.Launcher.LLDB"
      customLLDBInitFile = "$(SRCROOT)/Flutter/ephemeral/flutter_lldbinit"
      launchStyle = "0"
      useCustomWorkingDirectory = "NO"
      ignoresPersistentStateOnLaunch = "NO"
      debugDocumentVersioning = "YES"
      debugServiceExtension = "internal"
      enableGPUValidationMode = "1"
      allowLocationSimulation = "YES">
      <BuildableProductRunnable
         runnableDebuggingMode = "0">
         <BuildableReference
            BuildableIdentifier = "primary"
            BlueprintIdentifier = "97C146ED1CF9000F007C117D"
            BuildableName = "Runner.app"
            BlueprintName = "Runner"
            ReferencedContainer = "container:Runner.xcodeproj">
         </BuildableReference>
      </BuildableProductRunnable>
   </LaunchAction>
   <ProfileAction
      buildConfiguration = "Profile"
      shouldUseLaunchSchemeArgsEnv = "YES"
      savedToolIdentifier = ""
      useCustomWorkingDirectory = "NO"
      debugDocumentVersioning = "YES">
      <BuildableProductRunnable
         runnableDebuggingMode = "0">
         <BuildableReference
            BuildableIdentifier = "primary"
            BlueprintIdentifier = "97C146ED1CF9000F007C117D"
            BuildableName = "Runner.app"
            BlueprintName = "Runner"
            ReferencedContainer = "container:Runner.xcodeproj">
         </BuildableReference>
      </BuildableProductRunnable>
   </ProfileAction>
   <AnalyzeAction
      buildConfiguration = "Debug">
   </AnalyzeAction>
   <ArchiveAction
      buildConfiguration = "Release"
      revealArchiveInOrganizer = "YES">
   </ArchiveAction>
</Scheme>

</file>
<file path="ios/Runner.xcodeproj/project.pbxproj">
// !$*UTF8*$!
{
	archiveVersion = 1;
	classes = {
	};
	objectVersion = 54;
	objects = {

/* Begin PBXBuildFile section */
		1498D2341E8E89220040F4C2 /* GeneratedPluginRegistrant.m in Sources */ = {isa = PBXBuildFile; fileRef = 1498D2331E8E89220040F4C2 /* GeneratedPluginRegistrant.m */; };
		2A073B64EB22055EB3C7E006 /* Pods_Runner.framework in Frameworks */ = {isa = PBXBuildFile; fileRef = E69FE648E0488A0A422C89BB /* Pods_Runner.framework */; };
		331C808B294A63AB00263BE5 /* RunnerTests.swift in Sources */ = {isa = PBXBuildFile; fileRef = 331C807B294A618700263BE5 /* RunnerTests.swift */; };
		3B3967161E833CAA004F5970 /* AppFrameworkInfo.plist in Resources */ = {isa = PBXBuildFile; fileRef = 3B3967151E833CAA004F5970 /* AppFrameworkInfo.plist */; };
		74858FAF1ED2DC5600515810 /* AppDelegate.swift in Sources */ = {isa = PBXBuildFile; fileRef = 74858FAE1ED2DC5600515810 /* AppDelegate.swift */; };
		97C146FC1CF9000F007C117D /* Main.storyboard in Resources */ = {isa = PBXBuildFile; fileRef = 97C146FA1CF9000F007C117D /* Main.storyboard */; };
		97C146FE1CF9000F007C117D /* Assets.xcassets in Resources */ = {isa = PBXBuildFile; fileRef = 97C146FD1CF9000F007C117D /* Assets.xcassets */; };
		97C147011CF9000F007C117D /* LaunchScreen.storyboard in Resources */ = {isa = PBXBuildFile; fileRef = 97C146FF1CF9000F007C117D /* LaunchScreen.storyboard */; };
		BD527DBF300E516255B80EE6 /* Pods_RunnerTests.framework in Frameworks */ = {isa = PBXBuildFile; fileRef = 83BC731D84BB9F63A60BC7E3 /* Pods_RunnerTests.framework */; };
/* End PBXBuildFile section */

/* Begin PBXContainerItemProxy section */
		331C8085294A63A400263BE5 /* PBXContainerItemProxy */ = {
			isa = PBXContainerItemProxy;
			containerPortal = 97C146E61CF9000F007C117D /* Project object */;
			proxyType = 1;
			remoteGlobalIDString = 97C146ED1CF9000F007C117D;
			remoteInfo = Runner;
		};
/* End PBXContainerItemProxy section */

/* Begin PBXCopyFilesBuildPhase section */
		9705A1C41CF9048500538489 /* Embed Frameworks */ = {
			isa = PBXCopyFilesBuildPhase;
			buildActionMask = 2147483647;
			dstPath = "";
			dstSubfolderSpec = 10;
			files = (
			);
			name = "Embed Frameworks";
			runOnlyForDeploymentPostprocessing = 0;
		};
/* End PBXCopyFilesBuildPhase section */

/* Begin PBXFileReference section */
		1498D2321E8E86230040F4C2 /* GeneratedPluginRegistrant.h */ = {isa = PBXFileReference; lastKnownFileType = sourcecode.c.h; path = GeneratedPluginRegistrant.h; sourceTree = "<group>"; };
		1498D2331E8E89220040F4C2 /* GeneratedPluginRegistrant.m */ = {isa = PBXFileReference; fileEncoding = 4; lastKnownFileType = sourcecode.c.objc; path = GeneratedPluginRegistrant.m; sourceTree = "<group>"; };
		331C807B294A618700263BE5 /* RunnerTests.swift */ = {isa = PBXFileReference; lastKnownFileType = sourcecode.swift; path = RunnerTests.swift; sourceTree = "<group>"; };
		331C8081294A63A400263BE5 /* RunnerTests.xctest */ = {isa = PBXFileReference; explicitFileType = wrapper.cfbundle; includeInIndex = 0; path = RunnerTests.xctest; sourceTree = BUILT_PRODUCTS_DIR; };
		3B3967151E833CAA004F5970 /* AppFrameworkInfo.plist */ = {isa = PBXFileReference; fileEncoding = 4; lastKnownFileType = text.plist.xml; name = AppFrameworkInfo.plist; path = Flutter/AppFrameworkInfo.plist; sourceTree = "<group>"; };
		5577A353B8D87507670F093F /* Pods-Runner.release.xcconfig */ = {isa = PBXFileReference; includeInIndex = 1; lastKnownFileType = text.xcconfig; name = "Pods-Runner.release.xcconfig"; path = "Target Support Files/Pods-Runner/Pods-Runner.release.xcconfig"; sourceTree = "<group>"; };
		57AB5FC50E46EF6920598980 /* Pods-Runner.profile.xcconfig */ = {isa = PBXFileReference; includeInIndex = 1; lastKnownFileType = text.xcconfig; name = "Pods-Runner.profile.xcconfig"; path = "Target Support Files/Pods-Runner/Pods-Runner.profile.xcconfig"; sourceTree = "<group>"; };
		6747BB8FF052CCF4DFE0B20C /* Pods-RunnerTests.profile.xcconfig */ = {isa = PBXFileReference; includeInIndex = 1; lastKnownFileType = text.xcconfig; name = "Pods-RunnerTests.profile.xcconfig"; path = "Target Support Files/Pods-RunnerTests/Pods-RunnerTests.profile.xcconfig"; sourceTree = "<group>"; };
		74858FAD1ED2DC5600515810 /* Runner-Bridging-Header.h */ = {isa = PBXFileReference; lastKnownFileType = sourcecode.c.h; path = "Runner-Bridging-Header.h"; sourceTree = "<group>"; };
		74858FAE1ED2DC5600515810 /* AppDelegate.swift */ = {isa = PBXFileReference; fileEncoding = 4; lastKnownFileType = sourcecode.swift; path = AppDelegate.swift; sourceTree = "<group>"; };
		7AFA3C8E1D35360C0083082E /* Release.xcconfig */ = {isa = PBXFileReference; lastKnownFileType = text.xcconfig; name = Release.xcconfig; path = Flutter/Release.xcconfig; sourceTree = "<group>"; };
		83BC731D84BB9F63A60BC7E3 /* Pods_RunnerTests.framework */ = {isa = PBXFileReference; explicitFileType = wrapper.framework; includeInIndex = 0; path = Pods_RunnerTests.framework; sourceTree = BUILT_PRODUCTS_DIR; };
		9740EEB21CF90195004384FC /* Debug.xcconfig */ = {isa = PBXFileReference; fileEncoding = 4; lastKnownFileType = text.xcconfig; name = Debug.xcconfig; path = Flutter/Debug.xcconfig; sourceTree = "<group>"; };
		9740EEB31CF90195004384FC /* Generated.xcconfig */ = {isa = PBXFileReference; fileEncoding = 4; lastKnownFileType = text.xcconfig; name = Generated.xcconfig; path = Flutter/Generated.xcconfig; sourceTree = "<group>"; };
		97C146EE1CF9000F007C117D /* Runner.app */ = {isa = PBXFileReference; explicitFileType = wrapper.application; includeInIndex = 0; path = Runner.app; sourceTree = BUILT_PRODUCTS_DIR; };
		97C146FB1CF9000F007C117D /* Base */ = {isa = PBXFileReference; lastKnownFileType = file.storyboard; name = Base; path = Base.lproj/Main.storyboard; sourceTree = "<group>"; };
		97C146FD1CF9000F007C117D /* Assets.xcassets */ = {isa = PBXFileReference; lastKnownFileType = folder.assetcatalog; path = Assets.xcassets; sourceTree = "<group>"; };
		97C147001CF9000F007C117D /* Base */ = {isa = PBXFileReference; lastKnownFileType = file.storyboard; name = Base; path = Base.lproj/LaunchScreen.storyboard; sourceTree = "<group>"; };
		97C147021CF9000F007C117D /* Info.plist */ = {isa = PBXFileReference; lastKnownFileType = text.plist.xml; path = Info.plist; sourceTree = "<group>"; };
		BEBC63C227661BFC844B158A /* Pods-Runner.debug.xcconfig */ = {isa = PBXFileReference; includeInIndex = 1; lastKnownFileType = text.xcconfig; name = "Pods-Runner.debug.xcconfig"; path = "Target Support Files/Pods-Runner/Pods-Runner.debug.xcconfig"; sourceTree = "<group>"; };
		BFBF87E8CD67764761C7E59E /* Pods-RunnerTests.debug.xcconfig */ = {isa = PBXFileReference; includeInIndex = 1; lastKnownFileType = text.xcconfig; name = "Pods-RunnerTests.debug.xcconfig"; path = "Target Support Files/Pods-RunnerTests/Pods-RunnerTests.debug.xcconfig"; sourceTree = "<group>"; };
		C0995A156BC52BC74D084E19 /* Pods-RunnerTests.release.xcconfig */ = {isa = PBXFileReference; includeInIndex = 1; lastKnownFileType = text.xcconfig; name = "Pods-RunnerTests.release.xcconfig"; path = "Target Support Files/Pods-RunnerTests/Pods-RunnerTests.release.xcconfig"; sourceTree = "<group>"; };
		E69FE648E0488A0A422C89BB /* Pods_Runner.framework */ = {isa = PBXFileReference; explicitFileType = wrapper.framework; includeInIndex = 0; path = Pods_Runner.framework; sourceTree = BUILT_PRODUCTS_DIR; };
/* End PBXFileReference section */

/* Begin PBXFrameworksBuildPhase section */
		06910BD236D1A6440C46FED2 /* Frameworks */ = {
			isa = PBXFrameworksBuildPhase;
			buildActionMask = 2147483647;
			files = (
				BD527DBF300E516255B80EE6 /* Pods_RunnerTests.framework in Frameworks */,
			);
			runOnlyForDeploymentPostprocessing = 0;
		};
		97C146EB1CF9000F007C117D /* Frameworks */ = {
			isa = PBXFrameworksBuildPhase;
			buildActionMask = 2147483647;
			files = (
				2A073B64EB22055EB3C7E006 /* Pods_Runner.framework in Frameworks */,
			);
			runOnlyForDeploymentPostprocessing = 0;
		};
/* End PBXFrameworksBuildPhase section */

/* Begin PBXGroup section */
		331C8082294A63A400263BE5 /* RunnerTests */ = {
			isa = PBXGroup;
			children = (
				331C807B294A618700263BE5 /* RunnerTests.swift */,
			);
			path = RunnerTests;
			sourceTree = "<group>";
		};
		48591185C4280C64028EDF0E /* Pods */ = {
			isa = PBXGroup;
			children = (
				BEBC63C227661BFC844B158A /* Pods-Runner.debug.xcconfig */,
				5577A353B8D87507670F093F /* Pods-Runner.release.xcconfig */,
				57AB5FC50E46EF6920598980 /* Pods-Runner.profile.xcconfig */,
				BFBF87E8CD67764761C7E59E /* Pods-RunnerTests.debug.xcconfig */,
				C0995A156BC52BC74D084E19 /* Pods-RunnerTests.release.xcconfig */,
				6747BB8FF052CCF4DFE0B20C /* Pods-RunnerTests.profile.xcconfig */,
			);
			path = Pods;
			sourceTree = "<group>";
		};
		9740EEB11CF90186004384FC /* Flutter */ = {
			isa = PBXGroup;
			children = (
				3B3967151E833CAA004F5970 /* AppFrameworkInfo.plist */,
				9740EEB21CF90195004384FC /* Debug.xcconfig */,
				7AFA3C8E1D35360C0083082E /* Release.xcconfig */,
				9740EEB31CF90195004384FC /* Generated.xcconfig */,
			);
			name = Flutter;
			sourceTree = "<group>";
		};
		97C146E51CF9000F007C117D = {
			isa = PBXGroup;
			children = (
				9740EEB11CF90186004384FC /* Flutter */,
				97C146F01CF9000F007C117D /* Runner */,
				97C146EF1CF9000F007C117D /* Products */,
				331C8082294A63A400263BE5 /* RunnerTests */,
				48591185C4280C64028EDF0E /* Pods */,
				F3BA2B4CF8F5337684CA7B78 /* Frameworks */,
			);
			sourceTree = "<group>";
		};
		97C146EF1CF9000F007C117D /* Products */ = {
			isa = PBXGroup;
			children = (
				97C146EE1CF9000F007C117D /* Runner.app */,
				331C8081294A63A400263BE5 /* RunnerTests.xctest */,
			);
			name = Products;
			sourceTree = "<group>";
		};
		97C146F01CF9000F007C117D /* Runner */ = {
			isa = PBXGroup;
			children = (
				97C146FA1CF9000F007C117D /* Main.storyboard */,
				97C146FD1CF9000F007C117D /* Assets.xcassets */,
				97C146FF1CF9000F007C117D /* LaunchScreen.storyboard */,
				97C147021CF9000F007C117D /* Info.plist */,
				1498D2321E8E86230040F4C2 /* GeneratedPluginRegistrant.h */,
				1498D2331E8E89220040F4C2 /* GeneratedPluginRegistrant.m */,
				74858FAE1ED2DC5600515810 /* AppDelegate.swift */,
				74858FAD1ED2DC5600515810 /* Runner-Bridging-Header.h */,
			);
			path = Runner;
			sourceTree = "<group>";
		};
		F3BA2B4CF8F5337684CA7B78 /* Frameworks */ = {
			isa = PBXGroup;
			children = (
				E69FE648E0488A0A422C89BB /* Pods_Runner.framework */,
				83BC731D84BB9F63A60BC7E3 /* Pods_RunnerTests.framework */,
			);
			name = Frameworks;
			sourceTree = "<group>";
		};
/* End PBXGroup section */

/* Begin PBXNativeTarget section */
		331C8080294A63A400263BE5 /* RunnerTests */ = {
			isa = PBXNativeTarget;
			buildConfigurationList = 331C8087294A63A400263BE5 /* Build configuration list for PBXNativeTarget "RunnerTests" */;
			buildPhases = (
				8EAAE4F844651DF90DE7BA91 /* [CP] Check Pods Manifest.lock */,
				331C807D294A63A400263BE5 /* Sources */,
				331C807F294A63A400263BE5 /* Resources */,
				06910BD236D1A6440C46FED2 /* Frameworks */,
			);
			buildRules = (
			);
			dependencies = (
				331C8086294A63A400263BE5 /* PBXTargetDependency */,
			);
			name = RunnerTests;
			productName = RunnerTests;
			productReference = 331C8081294A63A400263BE5 /* RunnerTests.xctest */;
			productType = "com.apple.product-type.bundle.unit-test";
		};
		97C146ED1CF9000F007C117D /* Runner */ = {
			isa = PBXNativeTarget;
			buildConfigurationList = 97C147051CF9000F007C117D /* Build configuration list for PBXNativeTarget "Runner" */;
			buildPhases = (
				62594D2361E538E1F3F73A78 /* [CP] Check Pods Manifest.lock */,
				9740EEB61CF901F6004384FC /* Run Script */,
				97C146EA1CF9000F007C117D /* Sources */,
				97C146EB1CF9000F007C117D /* Frameworks */,
				97C146EC1CF9000F007C117D /* Resources */,
				9705A1C41CF9048500538489 /* Embed Frameworks */,
				3B06AD1E1E4923F5004D2608 /* Thin Binary */,
				6D04A329479EF7E6095179A3 /* [CP] Embed Pods Frameworks */,
				62A981D0A41CF3B535A329BD /* [CP] Copy Pods Resources */,
			);
			buildRules = (
			);
			dependencies = (
			);
			name = Runner;
			productName = Runner;
			productReference = 97C146EE1CF9000F007C117D /* Runner.app */;
			productType = "com.apple.product-type.application";
		};
/* End PBXNativeTarget section */

/* Begin PBXProject section */
		97C146E61CF9000F007C117D /* Project object */ = {
			isa = PBXProject;
			attributes = {
				BuildIndependentTargetsInParallel = YES;
				LastUpgradeCheck = 1510;
				ORGANIZATIONNAME = "";
				TargetAttributes = {
					331C8080294A63A400263BE5 = {
						CreatedOnToolsVersion = 14.0;
						TestTargetID = 97C146ED1CF9000F007C117D;
					};
					97C146ED1CF9000F007C117D = {
						CreatedOnToolsVersion = 7.3.1;
						LastSwiftMigration = 1100;
					};
				};
			};
			buildConfigurationList = 97C146E91CF9000F007C117D /* Build configuration list for PBXProject "Runner" */;
			compatibilityVersion = "Xcode 9.3";
			developmentRegion = en;
			hasScannedForEncodings = 0;
			knownRegions = (
				en,
				Base,
			);
			mainGroup = 97C146E51CF9000F007C117D;
			productRefGroup = 97C146EF1CF9000F007C117D /* Products */;
			projectDirPath = "";
			projectRoot = "";
			targets = (
				97C146ED1CF9000F007C117D /* Runner */,
				331C8080294A63A400263BE5 /* RunnerTests */,
			);
		};
/* End PBXProject section */

/* Begin PBXResourcesBuildPhase section */
		331C807F294A63A400263BE5 /* Resources */ = {
			isa = PBXResourcesBuildPhase;
			buildActionMask = 2147483647;
			files = (
			);
			runOnlyForDeploymentPostprocessing = 0;
		};
		97C146EC1CF9000F007C117D /* Resources */ = {
			isa = PBXResourcesBuildPhase;
			buildActionMask = 2147483647;
			files = (
				97C147011CF9000F007C117D /* LaunchScreen.storyboard in Resources */,
				3B3967161E833CAA004F5970 /* AppFrameworkInfo.plist in Resources */,
				97C146FE1CF9000F007C117D /* Assets.xcassets in Resources */,
				97C146FC1CF9000F007C117D /* Main.storyboard in Resources */,
			);
			runOnlyForDeploymentPostprocessing = 0;
		};
/* End PBXResourcesBuildPhase section */

/* Begin PBXShellScriptBuildPhase section */
		3B06AD1E1E4923F5004D2608 /* Thin Binary */ = {
			isa = PBXShellScriptBuildPhase;
			alwaysOutOfDate = 1;
			buildActionMask = 2147483647;
			files = (
			);
			inputPaths = (
				"${TARGET_BUILD_DIR}/${INFOPLIST_PATH}",
			);
			name = "Thin Binary";
			outputPaths = (
			);
			runOnlyForDeploymentPostprocessing = 0;
			shellPath = /bin/sh;
			shellScript = "/bin/sh \"$FLUTTER_ROOT/packages/flutter_tools/bin/xcode_backend.sh\" embed_and_thin";
		};
		62594D2361E538E1F3F73A78 /* [CP] Check Pods Manifest.lock */ = {
			isa = PBXShellScriptBuildPhase;
			buildActionMask = 2147483647;
			files = (
			);
			inputFileListPaths = (
			);
			inputPaths = (
				"${PODS_PODFILE_DIR_PATH}/Podfile.lock",
				"${PODS_ROOT}/Manifest.lock",
			);
			name = "[CP] Check Pods Manifest.lock";
			outputFileListPaths = (
			);
			outputPaths = (
				"$(DERIVED_FILE_DIR)/Pods-Runner-checkManifestLockResult.txt",
			);
			runOnlyForDeploymentPostprocessing = 0;
			shellPath = /bin/sh;
			shellScript = "diff \"${PODS_PODFILE_DIR_PATH}/Podfile.lock\" \"${PODS_ROOT}/Manifest.lock\" > /dev/null\nif [ $? != 0 ] ; then\n    # print error to STDERR\n    echo \"error: The sandbox is not in sync with the Podfile.lock. Run 'pod install' or update your CocoaPods installation.\" >&2\n    exit 1\nfi\n# This output is used by Xcode 'outputs' to avoid re-running this script phase.\necho \"SUCCESS\" > \"${SCRIPT_OUTPUT_FILE_0}\"\n";
			showEnvVarsInLog = 0;
		};
		62A981D0A41CF3B535A329BD /* [CP] Copy Pods Resources */ = {
			isa = PBXShellScriptBuildPhase;
			buildActionMask = 2147483647;
			files = (
			);
			inputFileListPaths = (
				"${PODS_ROOT}/Target Support Files/Pods-Runner/Pods-Runner-resources-${CONFIGURATION}-input-files.xcfilelist",
			);
			name = "[CP] Copy Pods Resources";
			outputFileListPaths = (
				"${PODS_ROOT}/Target Support Files/Pods-Runner/Pods-Runner-resources-${CONFIGURATION}-output-files.xcfilelist",
			);
			runOnlyForDeploymentPostprocessing = 0;
			shellPath = /bin/sh;
			shellScript = "\"${PODS_ROOT}/Target Support Files/Pods-Runner/Pods-Runner-resources.sh\"\n";
			showEnvVarsInLog = 0;
		};
		6D04A329479EF7E6095179A3 /* [CP] Embed Pods Frameworks */ = {
			isa = PBXShellScriptBuildPhase;
			buildActionMask = 2147483647;
			files = (
			);
			inputFileListPaths = (
				"${PODS_ROOT}/Target Support Files/Pods-Runner/Pods-Runner-frameworks-${CONFIGURATION}-input-files.xcfilelist",
			);
			name = "[CP] Embed Pods Frameworks";
			outputFileListPaths = (
				"${PODS_ROOT}/Target Support Files/Pods-Runner/Pods-Runner-frameworks-${CONFIGURATION}-output-files.xcfilelist",
			);
			runOnlyForDeploymentPostprocessing = 0;
			shellPath = /bin/sh;
			shellScript = "\"${PODS_ROOT}/Target Support Files/Pods-Runner/Pods-Runner-frameworks.sh\"\n";
			showEnvVarsInLog = 0;
		};
		8EAAE4F844651DF90DE7BA91 /* [CP] Check Pods Manifest.lock */ = {
			isa = PBXShellScriptBuildPhase;
			buildActionMask = 2147483647;
			files = (
			);
			inputFileListPaths = (
			);
			inputPaths = (
				"${PODS_PODFILE_DIR_PATH}/Podfile.lock",
				"${PODS_ROOT}/Manifest.lock",
			);
			name = "[CP] Check Pods Manifest.lock";
			outputFileListPaths = (
			);
			outputPaths = (
				"$(DERIVED_FILE_DIR)/Pods-RunnerTests-checkManifestLockResult.txt",
			);
			runOnlyForDeploymentPostprocessing = 0;
			shellPath = /bin/sh;
			shellScript = "diff \"${PODS_PODFILE_DIR_PATH}/Podfile.lock\" \"${PODS_ROOT}/Manifest.lock\" > /dev/null\nif [ $? != 0 ] ; then\n    # print error to STDERR\n    echo \"error: The sandbox is not in sync with the Podfile.lock. Run 'pod install' or update your CocoaPods installation.\" >&2\n    exit 1\nfi\n# This output is used by Xcode 'outputs' to avoid re-running this script phase.\necho \"SUCCESS\" > \"${SCRIPT_OUTPUT_FILE_0}\"\n";
			showEnvVarsInLog = 0;
		};
		9740EEB61CF901F6004384FC /* Run Script */ = {
			isa = PBXShellScriptBuildPhase;
			alwaysOutOfDate = 1;
			buildActionMask = 2147483647;
			files = (
			);
			inputPaths = (
			);
			name = "Run Script";
			outputPaths = (
			);
			runOnlyForDeploymentPostprocessing = 0;
			shellPath = /bin/sh;
			shellScript = "/bin/sh \"$FLUTTER_ROOT/packages/flutter_tools/bin/xcode_backend.sh\" build";
		};
/* End PBXShellScriptBuildPhase section */

/* Begin PBXSourcesBuildPhase section */
		331C807D294A63A400263BE5 /* Sources */ = {
			isa = PBXSourcesBuildPhase;
			buildActionMask = 2147483647;
			files = (
				331C808B294A63AB00263BE5 /* RunnerTests.swift in Sources */,
			);
			runOnlyForDeploymentPostprocessing = 0;
		};
		97C146EA1CF9000F007C117D /* Sources */ = {
			isa = PBXSourcesBuildPhase;
			buildActionMask = 2147483647;
			files = (
				74858FAF1ED2DC5600515810 /* AppDelegate.swift in Sources */,
				1498D2341E8E89220040F4C2 /* GeneratedPluginRegistrant.m in Sources */,
			);
			runOnlyForDeploymentPostprocessing = 0;
		};
/* End PBXSourcesBuildPhase section */

/* Begin PBXTargetDependency section */
		331C8086294A63A400263BE5 /* PBXTargetDependency */ = {
			isa = PBXTargetDependency;
			target = 97C146ED1CF9000F007C117D /* Runner */;
			targetProxy = 331C8085294A63A400263BE5 /* PBXContainerItemProxy */;
		};
/* End PBXTargetDependency section */

/* Begin PBXVariantGroup section */
		97C146FA1CF9000F007C117D /* Main.storyboard */ = {
			isa = PBXVariantGroup;
			children = (
				97C146FB1CF9000F007C117D /* Base */,
			);
			name = Main.storyboard;
			sourceTree = "<group>";
		};
		97C146FF1CF9000F007C117D /* LaunchScreen.storyboard */ = {
			isa = PBXVariantGroup;
			children = (
				97C147001CF9000F007C117D /* Base */,
			);
			name = LaunchScreen.storyboard;
			sourceTree = "<group>";
		};
/* End PBXVariantGroup section */

/* Begin XCBuildConfiguration section */
		249021D3217E4FDB00AE95B9 /* Profile */ = {
			isa = XCBuildConfiguration;
			buildSettings = {
				ALWAYS_SEARCH_USER_PATHS = NO;
				ASSETCATALOG_COMPILER_GENERATE_SWIFT_ASSET_SYMBOL_EXTENSIONS = YES;
				CLANG_ANALYZER_NONNULL = YES;
				CLANG_CXX_LANGUAGE_STANDARD = "gnu++0x";
				CLANG_CXX_LIBRARY = "libc++";
				CLANG_ENABLE_MODULES = YES;
				CLANG_ENABLE_OBJC_ARC = YES;
				CLANG_WARN_BLOCK_CAPTURE_AUTORELEASING = YES;
				CLANG_WARN_BOOL_CONVERSION = YES;
				CLANG_WARN_COMMA = YES;
				CLANG_WARN_CONSTANT_CONVERSION = YES;
				CLANG_WARN_DEPRECATED_OBJC_IMPLEMENTATIONS = YES;
				CLANG_WARN_DIRECT_OBJC_ISA_USAGE = YES_ERROR;
				CLANG_WARN_EMPTY_BODY = YES;
				CLANG_WARN_ENUM_CONVERSION = YES;
				CLANG_WARN_INFINITE_RECURSION = YES;
				CLANG_WARN_INT_CONVERSION = YES;
				CLANG_WARN_NON_LITERAL_NULL_CONVERSION = YES;
				CLANG_WARN_OBJC_IMPLICIT_RETAIN_SELF = YES;
				CLANG_WARN_OBJC_LITERAL_CONVERSION = YES;
				CLANG_WARN_OBJC_ROOT_CLASS = YES_ERROR;
				CLANG_WARN_RANGE_LOOP_ANALYSIS = YES;
				CLANG_WARN_STRICT_PROTOTYPES = YES;
				CLANG_WARN_SUSPICIOUS_MOVE = YES;
				CLANG_WARN_UNREACHABLE_CODE = YES;
				CLANG_WARN__DUPLICATE_METHOD_MATCH = YES;
				"CODE_SIGN_IDENTITY[sdk=iphoneos*]" = "iPhone Developer";
				COPY_PHASE_STRIP = NO;
				DEBUG_INFORMATION_FORMAT = "dwarf-with-dsym";
				ENABLE_NS_ASSERTIONS = NO;
				ENABLE_STRICT_OBJC_MSGSEND = YES;
				ENABLE_USER_SCRIPT_SANDBOXING = NO;
				GCC_C_LANGUAGE_STANDARD = gnu99;
				GCC_NO_COMMON_BLOCKS = YES;
				GCC_WARN_64_TO_32_BIT_CONVERSION = YES;
				GCC_WARN_ABOUT_RETURN_TYPE = YES_ERROR;
				GCC_WARN_UNDECLARED_SELECTOR = YES;
				GCC_WARN_UNINITIALIZED_AUTOS = YES_AGGRESSIVE;
				GCC_WARN_UNUSED_FUNCTION = YES;
				GCC_WARN_UNUSED_VARIABLE = YES;
				IPHONEOS_DEPLOYMENT_TARGET = 12.0;
				MTL_ENABLE_DEBUG_INFO = NO;
				SDKROOT = iphoneos;
				SUPPORTED_PLATFORMS = iphoneos;
				TARGETED_DEVICE_FAMILY = "1,2";
				VALIDATE_PRODUCT = YES;
			};
			name = Profile;
		};
		249021D4217E4FDB00AE95B9 /* Profile */ = {
			isa = XCBuildConfiguration;
			baseConfigurationReference = 7AFA3C8E1D35360C0083082E /* Release.xcconfig */;
			buildSettings = {
				ASSETCATALOG_COMPILER_APPICON_NAME = AppIcon;
				CLANG_ENABLE_MODULES = YES;
				CURRENT_PROJECT_VERSION = "$(FLUTTER_BUILD_NUMBER)";
				DEVELOPMENT_TEAM = VS8VZ7CBLZ;
				ENABLE_BITCODE = NO;
				INFOPLIST_FILE = Runner/Info.plist;
				LD_RUNPATH_SEARCH_PATHS = (
					"$(inherited)",
					"@executable_path/Frameworks",
				);
				PRODUCT_BUNDLE_IDENTIFIER = com.varyable.foodster;
				PRODUCT_NAME = "$(TARGET_NAME)";
				SWIFT_OBJC_BRIDGING_HEADER = "Runner/Runner-Bridging-Header.h";
				SWIFT_VERSION = 5.0;
				VERSIONING_SYSTEM = "apple-generic";
			};
			name = Profile;
		};
		331C8088294A63A400263BE5 /* Debug */ = {
			isa = XCBuildConfiguration;
			baseConfigurationReference = BFBF87E8CD67764761C7E59E /* Pods-RunnerTests.debug.xcconfig */;
			buildSettings = {
				BUNDLE_LOADER = "$(TEST_HOST)";
				CODE_SIGN_STYLE = Automatic;
				CURRENT_PROJECT_VERSION = 1;
				GENERATE_INFOPLIST_FILE = YES;
				MARKETING_VERSION = 1.0;
				PRODUCT_BUNDLE_IDENTIFIER = com.example.foodster.RunnerTests;
				PRODUCT_NAME = "$(TARGET_NAME)";
				SWIFT_ACTIVE_COMPILATION_CONDITIONS = DEBUG;
				SWIFT_OPTIMIZATION_LEVEL = "-Onone";
				SWIFT_VERSION = 5.0;
				TEST_HOST = "$(BUILT_PRODUCTS_DIR)/Runner.app/$(BUNDLE_EXECUTABLE_FOLDER_PATH)/Runner";
			};
			name = Debug;
		};
		331C8089294A63A400263BE5 /* Release */ = {
			isa = XCBuildConfiguration;
			baseConfigurationReference = C0995A156BC52BC74D084E19 /* Pods-RunnerTests.release.xcconfig */;
			buildSettings = {
				BUNDLE_LOADER = "$(TEST_HOST)";
				CODE_SIGN_STYLE = Automatic;
				CURRENT_PROJECT_VERSION = 1;
				GENERATE_INFOPLIST_FILE = YES;
				MARKETING_VERSION = 1.0;
				PRODUCT_BUNDLE_IDENTIFIER = com.example.foodster.RunnerTests;
				PRODUCT_NAME = "$(TARGET_NAME)";
				SWIFT_VERSION = 5.0;
				TEST_HOST = "$(BUILT_PRODUCTS_DIR)/Runner.app/$(BUNDLE_EXECUTABLE_FOLDER_PATH)/Runner";
			};
			name = Release;
		};
		331C808A294A63A400263BE5 /* Profile */ = {
			isa = XCBuildConfiguration;
			baseConfigurationReference = 6747BB8FF052CCF4DFE0B20C /* Pods-RunnerTests.profile.xcconfig */;
			buildSettings = {
				BUNDLE_LOADER = "$(TEST_HOST)";
				CODE_SIGN_STYLE = Automatic;
				CURRENT_PROJECT_VERSION = 1;
				GENERATE_INFOPLIST_FILE = YES;
				MARKETING_VERSION = 1.0;
				PRODUCT_BUNDLE_IDENTIFIER = com.example.foodster.RunnerTests;
				PRODUCT_NAME = "$(TARGET_NAME)";
				SWIFT_VERSION = 5.0;
				TEST_HOST = "$(BUILT_PRODUCTS_DIR)/Runner.app/$(BUNDLE_EXECUTABLE_FOLDER_PATH)/Runner";
			};
			name = Profile;
		};
		97C147031CF9000F007C117D /* Debug */ = {
			isa = XCBuildConfiguration;
			buildSettings = {
				ALWAYS_SEARCH_USER_PATHS = NO;
				ASSETCATALOG_COMPILER_GENERATE_SWIFT_ASSET_SYMBOL_EXTENSIONS = YES;
				CLANG_ANALYZER_NONNULL = YES;
				CLANG_CXX_LANGUAGE_STANDARD = "gnu++0x";
				CLANG_CXX_LIBRARY = "libc++";
				CLANG_ENABLE_MODULES = YES;
				CLANG_ENABLE_OBJC_ARC = YES;
				CLANG_WARN_BLOCK_CAPTURE_AUTORELEASING = YES;
				CLANG_WARN_BOOL_CONVERSION = YES;
				CLANG_WARN_COMMA = YES;
				CLANG_WARN_CONSTANT_CONVERSION = YES;
				CLANG_WARN_DEPRECATED_OBJC_IMPLEMENTATIONS = YES;
				CLANG_WARN_DIRECT_OBJC_ISA_USAGE = YES_ERROR;
				CLANG_WARN_EMPTY_BODY = YES;
				CLANG_WARN_ENUM_CONVERSION = YES;
				CLANG_WARN_INFINITE_RECURSION = YES;
				CLANG_WARN_INT_CONVERSION = YES;
				CLANG_WARN_NON_LITERAL_NULL_CONVERSION = YES;
				CLANG_WARN_OBJC_IMPLICIT_RETAIN_SELF = YES;
				CLANG_WARN_OBJC_LITERAL_CONVERSION = YES;
				CLANG_WARN_OBJC_ROOT_CLASS = YES_ERROR;
				CLANG_WARN_RANGE_LOOP_ANALYSIS = YES;
				CLANG_WARN_STRICT_PROTOTYPES = YES;
				CLANG_WARN_SUSPICIOUS_MOVE = YES;
				CLANG_WARN_UNREACHABLE_CODE = YES;
				CLANG_WARN__DUPLICATE_METHOD_MATCH = YES;
				"CODE_SIGN_IDENTITY[sdk=iphoneos*]" = "iPhone Developer";
				COPY_PHASE_STRIP = NO;
				DEBUG_INFORMATION_FORMAT = dwarf;
				ENABLE_STRICT_OBJC_MSGSEND = YES;
				ENABLE_TESTABILITY = YES;
				ENABLE_USER_SCRIPT_SANDBOXING = NO;
				GCC_C_LANGUAGE_STANDARD = gnu99;
				GCC_DYNAMIC_NO_PIC = NO;
				GCC_NO_COMMON_BLOCKS = YES;
				GCC_OPTIMIZATION_LEVEL = 0;
				GCC_PREPROCESSOR_DEFINITIONS = (
					"DEBUG=1",
					"$(inherited)",
				);
				GCC_WARN_64_TO_32_BIT_CONVERSION = YES;
				GCC_WARN_ABOUT_RETURN_TYPE = YES_ERROR;
				GCC_WARN_UNDECLARED_SELECTOR = YES;
				GCC_WARN_UNINITIALIZED_AUTOS = YES_AGGRESSIVE;
				GCC_WARN_UNUSED_FUNCTION = YES;
				GCC_WARN_UNUSED_VARIABLE = YES;
				IPHONEOS_DEPLOYMENT_TARGET = 12.0;
				MTL_ENABLE_DEBUG_INFO = YES;
				ONLY_ACTIVE_ARCH = YES;
				SDKROOT = iphoneos;
				TARGETED_DEVICE_FAMILY = "1,2";
			};
			name = Debug;
		};
		97C147041CF9000F007C117D /* Release */ = {
			isa = XCBuildConfiguration;
			buildSettings = {
				ALWAYS_SEARCH_USER_PATHS = NO;
				ASSETCATALOG_COMPILER_GENERATE_SWIFT_ASSET_SYMBOL_EXTENSIONS = YES;
				CLANG_ANALYZER_NONNULL = YES;
				CLANG_CXX_LANGUAGE_STANDARD = "gnu++0x";
				CLANG_CXX_LIBRARY = "libc++";
				CLANG_ENABLE_MODULES = YES;
				CLANG_ENABLE_OBJC_ARC = YES;
				CLANG_WARN_BLOCK_CAPTURE_AUTORELEASING = YES;
				CLANG_WARN_BOOL_CONVERSION = YES;
				CLANG_WARN_COMMA = YES;
				CLANG_WARN_CONSTANT_CONVERSION = YES;
				CLANG_WARN_DEPRECATED_OBJC_IMPLEMENTATIONS = YES;
				CLANG_WARN_DIRECT_OBJC_ISA_USAGE = YES_ERROR;
				CLANG_WARN_EMPTY_BODY = YES;
				CLANG_WARN_ENUM_CONVERSION = YES;
				CLANG_WARN_INFINITE_RECURSION = YES;
				CLANG_WARN_INT_CONVERSION = YES;
				CLANG_WARN_NON_LITERAL_NULL_CONVERSION = YES;
				CLANG_WARN_OBJC_IMPLICIT_RETAIN_SELF = YES;
				CLANG_WARN_OBJC_LITERAL_CONVERSION = YES;
				CLANG_WARN_OBJC_ROOT_CLASS = YES_ERROR;
				CLANG_WARN_RANGE_LOOP_ANALYSIS = YES;
				CLANG_WARN_STRICT_PROTOTYPES = YES;
				CLANG_WARN_SUSPICIOUS_MOVE = YES;
				CLANG_WARN_UNREACHABLE_CODE = YES;
				CLANG_WARN__DUPLICATE_METHOD_MATCH = YES;
				"CODE_SIGN_IDENTITY[sdk=iphoneos*]" = "iPhone Developer";
				COPY_PHASE_STRIP = NO;
				DEBUG_INFORMATION_FORMAT = "dwarf-with-dsym";
				ENABLE_NS_ASSERTIONS = NO;
				ENABLE_STRICT_OBJC_MSGSEND = YES;
				ENABLE_USER_SCRIPT_SANDBOXING = NO;
				GCC_C_LANGUAGE_STANDARD = gnu99;
				GCC_NO_COMMON_BLOCKS = YES;
				GCC_WARN_64_TO_32_BIT_CONVERSION = YES;
				GCC_WARN_ABOUT_RETURN_TYPE = YES_ERROR;
				GCC_WARN_UNDECLARED_SELECTOR = YES;
				GCC_WARN_UNINITIALIZED_AUTOS = YES_AGGRESSIVE;
				GCC_WARN_UNUSED_FUNCTION = YES;
				GCC_WARN_UNUSED_VARIABLE = YES;
				IPHONEOS_DEPLOYMENT_TARGET = 12.0;
				MTL_ENABLE_DEBUG_INFO = NO;
				SDKROOT = iphoneos;
				SUPPORTED_PLATFORMS = iphoneos;
				SWIFT_COMPILATION_MODE = wholemodule;
				SWIFT_OPTIMIZATION_LEVEL = "-O";
				TARGETED_DEVICE_FAMILY = "1,2";
				VALIDATE_PRODUCT = YES;
			};
			name = Release;
		};
		97C147061CF9000F007C117D /* Debug */ = {
			isa = XCBuildConfiguration;
			baseConfigurationReference = 9740EEB21CF90195004384FC /* Debug.xcconfig */;
			buildSettings = {
				ASSETCATALOG_COMPILER_APPICON_NAME = AppIcon;
				CLANG_ENABLE_MODULES = YES;
				CURRENT_PROJECT_VERSION = "$(FLUTTER_BUILD_NUMBER)";
				DEVELOPMENT_TEAM = VS8VZ7CBLZ;
				ENABLE_BITCODE = NO;
				INFOPLIST_FILE = Runner/Info.plist;
				LD_RUNPATH_SEARCH_PATHS = (
					"$(inherited)",
					"@executable_path/Frameworks",
				);
				PRODUCT_BUNDLE_IDENTIFIER = com.varyable.foodster;
				PRODUCT_NAME = "$(TARGET_NAME)";
				SWIFT_OBJC_BRIDGING_HEADER = "Runner/Runner-Bridging-Header.h";
				SWIFT_OPTIMIZATION_LEVEL = "-Onone";
				SWIFT_VERSION = 5.0;
				VERSIONING_SYSTEM = "apple-generic";
			};
			name = Debug;
		};
		97C147071CF9000F007C117D /* Release */ = {
			isa = XCBuildConfiguration;
			baseConfigurationReference = 7AFA3C8E1D35360C0083082E /* Release.xcconfig */;
			buildSettings = {
				ASSETCATALOG_COMPILER_APPICON_NAME = AppIcon;
				CLANG_ENABLE_MODULES = YES;
				CURRENT_PROJECT_VERSION = "$(FLUTTER_BUILD_NUMBER)";
				DEVELOPMENT_TEAM = VS8VZ7CBLZ;
				ENABLE_BITCODE = NO;
				INFOPLIST_FILE = Runner/Info.plist;
				LD_RUNPATH_SEARCH_PATHS = (
					"$(inherited)",
					"@executable_path/Frameworks",
				);
				PRODUCT_BUNDLE_IDENTIFIER = com.varyable.foodster;
				PRODUCT_NAME = "$(TARGET_NAME)";
				SWIFT_OBJC_BRIDGING_HEADER = "Runner/Runner-Bridging-Header.h";
				SWIFT_VERSION = 5.0;
				VERSIONING_SYSTEM = "apple-generic";
			};
			name = Release;
		};
/* End XCBuildConfiguration section */

/* Begin XCConfigurationList section */
		331C8087294A63A400263BE5 /* Build configuration list for PBXNativeTarget "RunnerTests" */ = {
			isa = XCConfigurationList;
			buildConfigurations = (
				331C8088294A63A400263BE5 /* Debug */,
				331C8089294A63A400263BE5 /* Release */,
				331C808A294A63A400263BE5 /* Profile */,
			);
			defaultConfigurationIsVisible = 0;
			defaultConfigurationName = Release;
		};
		97C146E91CF9000F007C117D /* Build configuration list for PBXProject "Runner" */ = {
			isa = XCConfigurationList;
			buildConfigurations = (
				97C147031CF9000F007C117D /* Debug */,
				97C147041CF9000F007C117D /* Release */,
				249021D3217E4FDB00AE95B9 /* Profile */,
			);
			defaultConfigurationIsVisible = 0;
			defaultConfigurationName = Release;
		};
		97C147051CF9000F007C117D /* Build configuration list for PBXNativeTarget "Runner" */ = {
			isa = XCConfigurationList;
			buildConfigurations = (
				97C147061CF9000F007C117D /* Debug */,
				97C147071CF9000F007C117D /* Release */,
				249021D4217E4FDB00AE95B9 /* Profile */,
			);
			defaultConfigurationIsVisible = 0;
			defaultConfigurationName = Release;
		};
/* End XCConfigurationList section */
	};
	rootObject = 97C146E61CF9000F007C117D /* Project object */;
}

</file>
<file path="ios/Runner.xcworkspace/xcshareddata/IDEWorkspaceChecks.plist">
<?xml version="1.0" encoding="UTF-8"?>
<!DOCTYPE plist PUBLIC "-//Apple//DTD PLIST 1.0//EN" "http://www.apple.com/DTDs/PropertyList-1.0.dtd">
<plist version="1.0">
<dict>
	<key>IDEDidComputeMac32BitWarning</key>
	<true/>
</dict>
</plist>

</file>
<file path="ios/Runner.xcworkspace/xcshareddata/WorkspaceSettings.xcsettings">
<?xml version="1.0" encoding="UTF-8"?>
<!DOCTYPE plist PUBLIC "-//Apple//DTD PLIST 1.0//EN" "http://www.apple.com/DTDs/PropertyList-1.0.dtd">
<plist version="1.0">
<dict>
	<key>PreviewsEnabled</key>
	<false/>
</dict>
</plist>

</file>
<file path="ios/Runner.xcworkspace/xcuserdata/varyable.xcuserdatad/UserInterfaceState.xcuserstate">
bplist00�        
X$versionY$archiverT$topX$objects ��_NSKeyedArchiver�  	UState���      - . / 0 1 2 3 4 5 6 7 8 N O P Q R S T U V W a b c d p q r s t x ~  � � � � � � � � � � � � � � � � � � � � � � � � � � � � � � � � � � � � � � �
 !",-.BCDEFGHIJKLMQabcdefghrstuy{~����������������������������������� #$(+159?@CYZ[\]^_`abcdefgU$null� 
     WNS.keysZNS.objectsV$class�  ���  ����$_$07725CA5-D33C-4241-9172-65AF500A8263_IDEWorkspaceDocument� 
    # ,�        ! "�����	�
��� $ % & $ (  & $�
���
��	��
�+_IDEWindowIsFullScreen^IDEWindowFrame_-IDEHasMigratedValuesFromNSRestorableStateData_&IDEWindowTabBarWasVisibleWithSingleTab_>IDEWorkspaceTabController_8172BE57-5710-4224-BEBA-592C6CC437E6_IDEActiveWorkspaceTabController_IDEWindowToolbarIsVisible_IDEWindowTabBarIsVisible_903 440 1400 900 0 0 3840 2135 	� 
   9 C ,� : ; < = > ? @ A B���������� & E F G H I J $ L���/�0�7�8�:�
�=�+_IDEShowNavigator_IDENavigatorArea_IDEUtilitiesWidth_IDEInspectorArea_IDENavigatorWidth\ViewDebugger_MemoryGraphDebugger_IDEShowUtilities]IDEEditorArea� 
   X \ ,� Y Z [���� ] ^ _��,�-�+_ Xcode.IDEKit.Navigator.Workspace_SelectedNavigator_GroupSelections� 
   e j ,� f g h i�� �!�"� k l m k�#�%�&�#�+_FilterStateByModeKey_LastNavigatorMode_UnfilterStateByModeKey_FilteredUIStateByModeKey� 
   u v ���$� y z { |Z$classnameX$classes\NSDictionary� { }XNSObject_IDENavigatorModeSolitary� 
   � � � ��'� ��(�$_IDENavigatorModeSolitary�  � � �_codablePlistRepresentation�*�)O�bplist00�_lastAccessedDateYitemState]selectedItems^scrollPosition3A�(>Ǡࠠ�	�
#        #�$      �
#@q      #@�(     $.<KTUVY\enqz                            �� y z � �_&ExplorableOutlineViewArchivableUIState� � � }_&ExplorableOutlineViewArchivableUIState_b_TtGCV16DVTExplorableKit26ExplorableOutlineViewTypes7UIState_VS_31ExplorableStateSavingIdentifier_� y z � �_NSMutableDictionary� � { }_ Xcode.IDEKit.Navigator.Workspace� 
   � � ,� ��.� ^�,�+_%Xcode.IDEKit.NavigatorGroup.Structure#@p@     � 
   � � ,� � ��1�2� � ��3�5�+_'userPreferredInspectorGroupExtensionIDs_!userPreferredCategoryExtensionIDs�   � ���4� y z � �WNSArray� � }�   � ���6� y z � �^NSMutableArray� � � }#@q      � 
   � � ,� ��9� &��+_ShowsOnlyVisibleViewObjects� 
   � � ,� � ��;�<� $ $�
�
�+_ShowsOnlyLeakedBlocks_XRShowsOnlyContentFromWorkspace� 
   � � ,� � � � � � � � � � π>�?�@�A�B�C�D�E�F�G� � � � � � � � � � ڀH�R�h�i�j�k���������+_IDEDefaultDebugArea_*BeforeComparisonMode_UserVisibleEditorMode_NavigationStyleZEditorMode_ EditorMultipleSplitPrimaryLayout_EditorAreaSplitStates_DebuggerSplitView_ DefaultPersistentRepresentations_ SelectedEditorAreaSplitIndexPath^MaximizedState� 
   � � ,� � � � ��I�J�K�L� � � � ��M�S�^�_�+XLeftView_IDESplitViewDebugAreaZLayoutModeYRightView� 
   � � ,� � � � ��N�O�P�Q� $ � � &�
�R�3��+_VariablesViewShowsRawValues_VariablesViewSelectedScope_ VariablesViewViewSortDescriptors_VariablesViewShowsType � 
  
 ,�	�T��U�+_DVTSplitViewItems�   ���V�\�6� 
   ��W�X�Y� &�Z��[�$]DVTIdentifier\DVTIsVisible_DVTViewMagnitudeXLeftView#@��     � 
  #' ��W�X�Y�( &�]��[�$YRightView� 
  /8 ,�01234567�`�a�b�c�d�e�f�g� $ $ $ & $ $ $ $�
�
�
��
�
�
�
�+_+IDEStructuredConsoleAreaLibraryEnabledState_-IDEStructuredConsoleAreaTimestampEnabledState_*IDEStructuredConsoleAreaPIDTIDEnabledState_,IDEStructuredConsoleAreaMetadataEnabledState_(IDEStructuredConsoleAreaTypeEnabledState_-IDEStructuredConsoleAreaSubsystemEnabledState_/IDEStructuredConsoleAreaProcessNameEnabledState_,IDEStructuredConsoleAreaCategoryEnabledStateZOpenInTabs _Layout_LeftToRight�  N ��O�l�6� 
  RY ,�STUVWX�m�n�o�p�q�r�Z[\Z^ $�s�t�{�s���
�+ZEditorMode_EditorTabBarState_EditorHistoryStacks]EditorMode13+[ItemKindKey_ShouldShowPullRequestComments � 
  im ,�jkl�u�v�w�n � Ҁx�R�R�+_TabsAsHistoryItems_SelectedTabIndex_DynamicTabIndex�  v ��w�y�4� z�z� y z|}VNSNull�| }�   ����|�6�� ��_currentEditorHistoryItem�}����� ����_navigableItemName_stateDictionary_documentNavigableItemName� � � �~� y z��_IDEEditorHistoryItem�� }_IDEEditorHistoryItem� y z��_IDEEditorHistoryStack�� }_IDEEditorHistoryStack_ItemKind_Editor� 
  �� ,���������+_DVTSplitViewItems�  � ���������6� 
  �� ��W�X�Y�� &�������$YIDEEditor#@��     � 
  �� ��W�X�Y�� &�������$_IDEDebuggerArea#@\�     � 
  �� ,���+��� -�_NSIndexPathLength_NSIndexPathValue��� y z��[NSIndexPath�� }[NSIndexPath � 
  �� ,��������������׀���������������������������� ���w � $� $���w� &�3�����y�R�
���
�������y����+_RecentEditorDocumentURLs_DefaultEditorStatesForURLs\ActiveScheme_ActiveProjectSetIdentifierKey_$RunDestinationArchitectureVisibility_%forceBuildForAllArchitecturesIfNeeded_DocumentWindows_EnableThreadGallery_WindowArrangementDebugInfo_RunContextRecents_ActiveRunDestination_ActiveProjectSetNameKey_SelectedWindows_BreakpointsActivated� 
  �� ,���+� 
  �� ���������$]IDENameStringVRunner�   �� ��4� 
  	 ,� ��
���+�  
 �� ��6� 
   ���������������$_IDERunContextRecentsSchemesKey_5IDERunContextRecentsLastUsedRunDestinationBySchemeKey_&IDERunContextRecentsRunDestinationsKey� 
    ,����!���+VRunner�% &'WNS.time#A�&��c���� y z)*VNSDate�) }� 
  ,. ,����/���+� 234YNS.string��_(00008110-00027DC83A87801E_iphoneos_arm64� y z67_NSMutableString�68 }XNSString� 
  :< ,�;���=���+_(00008110-00027DC83A87801E_iphoneos_arm64�% A'#A�&��k'��� 
  DN �EFGHIJKLM������������������� &P $PSTUV &����
������������$ZisEligible_targetDevicePlatform_targetDeviceIsWireless_targetSDKVariant_targetDeviceLocation_targetArchitectureYtargetSDK_targetDeviceModelCode_targetDeviceIsConcreteXiphoneos_2DVTCoreDevice-CDB7EF06-122D-53F5-876F-D5B32C3619C7Uarm64\iphoneos18.5ZiPhone14,3�  h ���6   " , 1 : ? Q V \ ^���  "$&Mdq���������������������5v������ !#%8:<>@BDFHJL_r��������	=Qcpy{}����������� 
!.3<Wdgilnp�����js���1:PWz����������������		3	<	=	?	H	P	U	^	_	a	j	y	�	�	�	�	�	�	�	�	�	�	�	�	�	�	�	�	�	�

(
=
?
A
C
E
G
I
K
M
O
Q
f
h
j
l
n
p
r
t
v
x
z
|
�
�
�
�
�+Nq��������������������6Sv������������������������
	

)
2
;
H
O
Q
S
U
\
^
`
b
d
n
p
}
�
�
�
�
�
�
�
�
�
�
�
�
�
�
�
�
�
�
�
�L{��7BDYbegiv��������������������(/135<>@BDYl~������������������579;=F]by�������������
 "$+-/13=FSZ\^`gikmo���������������#%')+-/13579;=?\^`bdfhjlnprtvxz����.@Vs����������	 ),.0=@BEGIRUWYfmoqsz|~����$-5>@IPUbegjlnw�������������&/1>QSUWY[]_acvxz|~�����������&?H}�����            j              �
</file>
<file path="ios/Runner.xcworkspace/contents.xcworkspacedata">
<?xml version="1.0" encoding="UTF-8"?>
<Workspace
   version = "1.0">
   <FileRef
      location = "group:Runner.xcodeproj">
   </FileRef>
   <FileRef
      location = "group:Pods/Pods.xcodeproj">
   </FileRef>
</Workspace>

</file>
<file path="ios/RunnerTests/RunnerTests.swift">
import Flutter
import UIKit
import XCTest

class RunnerTests: XCTestCase {

  func testExample() {
    // If you add code to the Runner application, consider adding tests here.
    // See https://developer.apple.com/documentation/xctest for more information about using XCTest.
  }

}

</file>
<file path="ios/.gitignore">
**/dgph
*.mode1v3
*.mode2v3
*.moved-aside
*.pbxuser
*.perspectivev3
**/*sync/
.sconsign.dblite
.tags*
**/.vagrant/
**/DerivedData/
Icon?
**/Pods/
**/.symlinks/
profile
xcuserdata
**/.generated/
Flutter/App.framework
Flutter/Flutter.framework
Flutter/Flutter.podspec
Flutter/Generated.xcconfig
Flutter/ephemeral/
Flutter/app.flx
Flutter/app.zip
Flutter/flutter_assets/
Flutter/flutter_export_environment.sh
ServiceDefinitions.json
Runner/GeneratedPluginRegistrant.*

# Exceptions to above rules.
!default.mode1v3
!default.mode2v3
!default.pbxuser
!default.perspectivev3

</file>
<file path="ios/Podfile">
# Uncomment this line to define a global platform for your project
platform :ios, '14.0'

# CocoaPods analytics sends network stats synchronously affecting flutter build latency.
ENV['COCOAPODS_DISABLE_STATS'] = 'true'

project 'Runner', {
  'Debug' => :debug,
  'Profile' => :release,
  'Release' => :release,
}

def flutter_root
  generated_xcode_build_settings_path = File.expand_path(File.join('..', 'Flutter', 'Generated.xcconfig'), __FILE__)
  unless File.exist?(generated_xcode_build_settings_path)
    raise "#{generated_xcode_build_settings_path} must exist. If you're running pod install manually, make sure flutter pub get is executed first"
  end

  File.foreach(generated_xcode_build_settings_path) do |line|
    matches = line.match(/FLUTTER_ROOT\=(.*)/)
    return matches[1].strip if matches
  end
  raise "FLUTTER_ROOT not found in #{generated_xcode_build_settings_path}. Try deleting Generated.xcconfig, then run flutter pub get"
end

require File.expand_path(File.join('packages', 'flutter_tools', 'bin', 'podhelper'), flutter_root)

flutter_ios_podfile_setup

target 'Runner' do
  use_frameworks!

  flutter_install_all_ios_pods File.dirname(File.realpath(__FILE__))
  target 'RunnerTests' do
    inherit! :search_paths
  end
end

post_install do |installer|
  installer.pods_project.targets.each do |target|
    flutter_additional_ios_build_settings(target)
  end
end

</file>
<file path="ios/Podfile.lock">
PODS:
  - app_links (0.0.2):
    - Flutter
  - connectivity_plus (0.0.1):
    - Flutter
    - ReachabilitySwift
  - Flutter (1.0.0)
  - flutter_native_splash (2.4.3):
    - Flutter
  - flutter_secure_storage (6.0.0):
    - Flutter
  - Google-Maps-iOS-Utils (5.0.0):
    - GoogleMaps (~> 8.0)
  - google_maps_flutter_ios (0.0.1):
    - Flutter
    - Google-Maps-iOS-Utils (< 7.0, >= 5.0)
    - GoogleMaps (< 10.0, >= 8.4)
  - GoogleMaps (8.4.0):
    - GoogleMaps/Maps (= 8.4.0)
  - GoogleMaps/Base (8.4.0)
  - GoogleMaps/Maps (8.4.0):
    - GoogleMaps/Base
  - path_provider_foundation (0.0.1):
    - Flutter
    - FlutterMacOS
  - ReachabilitySwift (5.2.4)
  - shared_preferences_foundation (0.0.1):
    - Flutter
    - FlutterMacOS
  - sqflite_darwin (0.0.4):
    - Flutter
    - FlutterMacOS
  - url_launcher_ios (0.0.1):
    - Flutter

DEPENDENCIES:
  - app_links (from `.symlinks/plugins/app_links/ios`)
  - connectivity_plus (from `.symlinks/plugins/connectivity_plus/ios`)
  - Flutter (from `Flutter`)
  - flutter_native_splash (from `.symlinks/plugins/flutter_native_splash/ios`)
  - flutter_secure_storage (from `.symlinks/plugins/flutter_secure_storage/ios`)
  - google_maps_flutter_ios (from `.symlinks/plugins/google_maps_flutter_ios/ios`)
  - path_provider_foundation (from `.symlinks/plugins/path_provider_foundation/darwin`)
  - shared_preferences_foundation (from `.symlinks/plugins/shared_preferences_foundation/darwin`)
  - sqflite_darwin (from `.symlinks/plugins/sqflite_darwin/darwin`)
  - url_launcher_ios (from `.symlinks/plugins/url_launcher_ios/ios`)

SPEC REPOS:
  trunk:
    - Google-Maps-iOS-Utils
    - GoogleMaps
    - ReachabilitySwift

EXTERNAL SOURCES:
  app_links:
    :path: ".symlinks/plugins/app_links/ios"
  connectivity_plus:
    :path: ".symlinks/plugins/connectivity_plus/ios"
  Flutter:
    :path: Flutter
  flutter_native_splash:
    :path: ".symlinks/plugins/flutter_native_splash/ios"
  flutter_secure_storage:
    :path: ".symlinks/plugins/flutter_secure_storage/ios"
  google_maps_flutter_ios:
    :path: ".symlinks/plugins/google_maps_flutter_ios/ios"
  path_provider_foundation:
    :path: ".symlinks/plugins/path_provider_foundation/darwin"
  shared_preferences_foundation:
    :path: ".symlinks/plugins/shared_preferences_foundation/darwin"
  sqflite_darwin:
    :path: ".symlinks/plugins/sqflite_darwin/darwin"
  url_launcher_ios:
    :path: ".symlinks/plugins/url_launcher_ios/ios"

SPEC CHECKSUMS:
  app_links: f3e17e4ee5e357b39d8b95290a9b2c299fca71c6
  connectivity_plus: bf0076dd84a130856aa636df1c71ccaff908fa1d
  Flutter: e0871f40cf51350855a761d2e70bf5af5b9b5de7
  flutter_native_splash: df59bb2e1421aa0282cb2e95618af4dcb0c56c29
  flutter_secure_storage: d33dac7ae2ea08509be337e775f6b59f1ff45f12
  Google-Maps-iOS-Utils: 66d6de12be1ce6d3742a54661e7a79cb317a9321
  google_maps_flutter_ios: e31555a04d1986ab130f2b9f24b6cdc861acc6d3
  GoogleMaps: 8939898920281c649150e0af74aa291c60f2e77d
  path_provider_foundation: 2b6b4c569c0fb62ec74538f866245ac84301af46
  ReachabilitySwift: 32793e867593cfc1177f5d16491e3a197d2fccda
  shared_preferences_foundation: fcdcbc04712aee1108ac7fda236f363274528f78
  sqflite_darwin: 5a7236e3b501866c1c9befc6771dfd73ffb8702d
  url_launcher_ios: 5334b05cef931de560670eeae103fd3e431ac3fe

PODFILE CHECKSUM: e30f02f9d1c72c47bb6344a0a748c9d268180865

COCOAPODS: 1.16.2

</file>
<file path="lib/core/config/app_config.dart">
import 'package:flutter_dotenv/flutter_dotenv.dart';
import 'package:flutter/foundation.dart';

/// Configuration for the app
class AppConfig {
  // Supabase configuration
  static String get supabaseUrl => dotenv.env['SUPABASE_URL'] ?? '';
  static String get supabaseAnonKey => dotenv.env['SUPABASE_ANON_KEY'] ?? '';

  // API Keys
  static String get edamamApiKey => dotenv.env['EDAMAM_API_KEY'] ?? '';
  static String get spoonacularApiKey =>
      dotenv.env['SPOONACULAR_API_KEY'] ?? '';
  static String get krogerClientId => dotenv.env['KROGER_CLIENT_ID'] ?? '';
  static String get krogerClientSecret =>
      dotenv.env['KROGER_CLIENT_SECRET'] ?? '';
  static String get googleMapsApiKey => dotenv.env['GOOGLE_MAPS_API_KEY'] ?? '';

  // Environment
  static bool get isProduction => bool.fromEnvironment('dart.vm.product');
  static bool get isDevelopment => !isProduction;

  // Feature flags
  static bool get enableOfflineMode => true;
  static bool get enableReverseGroceryBudgeting => true;
  static bool get enableStoreComparison => true;

  // Timeouts
  static const int connectionTimeout = 30000; // milliseconds
  static const int receiveTimeout = 30000; // milliseconds

  /// Auth is now optional - handled in profile section
  static const bool skipAuth = false; // Auth is optional

  /// Initialize configuration
  static Future<void> init() async {
    try {
      await dotenv.load(fileName: '.env');
      debugPrint('AppConfig initialized successfully.');
    } catch (e) {
      debugPrint('Error loading configuration: $e');
    }
  }
}

</file>
<file path="lib/core/constants/app_constants.dart">
/// Application constants
class AppConstants {
  // App Info
  static const String appName = 'Foodster';
  static const String appVersion = '1.0.0';

  // API Endpoints
  static const String edamamBaseUrl = 'https://api.edamam.com';
  static const String spoonacularBaseUrl = 'https://api.spoonacular.com';
  static const String krogerBaseUrl = 'https://api.kroger.com/v1';

  // Edge Function Names
  static const String getNutritionFunction = 'get-nutrition';

  // Local Storage Keys
  static const String authTokenKey = 'auth_token';
  static const String userPrefsKey = 'user_preferences';
  static const String mealPlanKey = 'meal_plan';
  static const String groceryListKey = 'grocery_list';

  // Default Values
  static const int defaultBudgetWarningPercentage = 85;
  static const int defaultMealPlanDurationDays = 7;
}

</file>
<file path="lib/core/constants/message_constants.dart">
/// Constants for API error messages
class ApiErrors {
  static const String networkError =
      'Network error. Please check your internet connection.';
  static const String serverError = 'Server error. Please try again later.';
  static const String authError = 'Authentication error. Please sign in again.';
  static const String notFoundError = 'The requested resource was not found.';
  static const String timeoutError = 'Request timed out. Please try again.';
  static const String unknownError =
      'An unknown error occurred. Please try again.';
  static const String noInternetConnection =
      'No internet connection available.';
  static const String invalidCredentials = 'Invalid email or password.';
}

/// Constants for validation error messages
class ValidationErrors {
  static const String emptyField = 'This field cannot be empty';
  static const String invalidEmail = 'Please enter a valid email address';
  static const String passwordTooShort =
      'Password must be at least 8 characters';
  static const String passwordsDoNotMatch = 'Passwords do not match';
  static const String invalidName = 'Please enter a valid name';
  static const String invalidNumber = 'Please enter a valid number';
}

/// Constants for success messages
class SuccessMessages {
  static const String signInSuccess = 'Successfully signed in';
  static const String signUpSuccess = 'Account created successfully';
  static const String profileUpdateSuccess = 'Profile updated successfully';
  static const String mealPlanCreated = 'Meal plan created successfully';
  static const String budgetUpdated = 'Budget updated successfully';
  static const String groceryListUpdated = 'Grocery list updated successfully';
}

</file>
<file path="lib/core/di/injection_container.dart">
import 'package:get_it/get_it.dart';

import '../../features/recipes/data/repositories/recipe_repository_impl.dart';
import '../../features/recipes/domain/repositories/recipe_repository.dart';
import '../../features/recipes/domain/usecases/recipe_usecases.dart';
import '../../features/recipes/presentation/bloc/recipe_detail_bloc.dart';

import '../../features/nutrition/data/services/nutrition_service.dart';
import '../../features/nutrition/data/repositories/nutrition_repository_impl.dart';
import '../../features/nutrition/domain/repositories/nutrition_repository.dart';
import '../../features/nutrition/domain/usecases/get_nutrition_info.dart';
import '../../features/nutrition/presentation/bloc/nutrition_bloc.dart';

import '../../features/budget_tracking/data/repositories/budget_repository_impl.dart';
import '../../features/budget_tracking/domain/repositories/budget_repository.dart';
import '../../features/budget_tracking/domain/usecases/budget_usecases.dart';
import '../../features/budget_tracking/presentation/bloc/budget_bloc.dart';

/// Service locator instance
final sl = GetIt.instance;

/// Initialize dependencies
Future<void> init() async {
  // BLoCs
  sl.registerFactory(
    () => RecipeDetailBloc(
      getRecipeById: sl<GetRecipeById>(),
      toggleFavoriteRecipe: sl<ToggleFavoriteRecipe>(),
    ),
  );

  sl.registerFactory(
    () => NutritionBloc(getNutritionInfo: sl<GetNutritionInfo>()),
  );

  sl.registerFactory(
    () => BudgetBloc(
      getCurrentBudget: sl<GetCurrentBudget>(),
      createBudget: sl<CreateBudget>(),
      updateBudgetCategories: sl<UpdateBudgetCategories>(),
      recordSpending: sl<RecordSpending>(),
    ),
  );

  // Use cases - Recipes
  sl.registerLazySingleton(() => GetRecipeById(sl<RecipeRepository>()));
  sl.registerLazySingleton(() => GetAllRecipes(sl<RecipeRepository>()));
  sl.registerLazySingleton(() => GetRecipesByMealType(sl<RecipeRepository>()));
  sl.registerLazySingleton(() => GetFavoriteRecipes(sl<RecipeRepository>()));
  sl.registerLazySingleton(() => ToggleFavoriteRecipe(sl<RecipeRepository>()));
  sl.registerLazySingleton(() => SearchRecipes(sl<RecipeRepository>()));

  // Use cases - Nutrition
  sl.registerLazySingleton(() => GetNutritionInfo(sl<NutritionRepository>()));

  // Use cases - Budget
  sl.registerLazySingleton(() => GetCurrentBudget(sl<BudgetRepository>()));
  sl.registerLazySingleton(() => CreateBudget(sl<BudgetRepository>()));
  sl.registerLazySingleton(
    () => UpdateBudgetCategories(sl<BudgetRepository>()),
  );
  sl.registerLazySingleton(() => RecordSpending(sl<BudgetRepository>()));

  // Repositories
  sl.registerLazySingleton<RecipeRepository>(() => RecipeRepositoryImpl());
  sl.registerLazySingleton<NutritionRepository>(
    () => NutritionRepositoryImpl(nutritionService: sl()),
  );
  sl.registerLazySingleton<BudgetRepository>(() => BudgetRepositoryImpl());

  // Services
  sl.registerLazySingleton<NutritionService>(() => NutritionService());

  // External dependencies
  // Future registrations for data sources, APIs, etc.
}

</file>
<file path="lib/core/error/failure.dart">
import 'package:equatable/equatable.dart';

abstract class Failure extends Equatable {
  final String message;

  const Failure(this.message);

  @override
  List<Object> get props => [message];
}

class ServerFailure extends Failure {
  const ServerFailure(String message) : super(message);
}

class CacheFailure extends Failure {
  const CacheFailure(String message) : super(message);
}

class NetworkFailure extends Failure {
  const NetworkFailure(String message) : super(message);
}

class InputFailure extends Failure {
  const InputFailure(String message) : super(message);
}

</file>
<file path="lib/core/errors/exceptions.dart">
/// Custom exceptions for data layer
class ServerException implements Exception {
  final String message;
  ServerException(this.message);
}

class NetworkException implements Exception {
  final String message;
  NetworkException(this.message);
}

class CacheException implements Exception {
  final String message;
  CacheException(this.message);
}

class AuthException implements Exception {
  final String message;
  AuthException(this.message);
}

class ValidationException implements Exception {
  final String message;
  ValidationException(this.message);
}

class NotFoundException implements Exception {
  final String message;
  NotFoundException(this.message);
}

class PermissionException implements Exception {
  final String message;
  PermissionException(this.message);
}

</file>
<file path="lib/core/errors/failures.dart">
import 'package:equatable/equatable.dart';

/// Base failure class for domain layer errors
abstract class Failure extends Equatable {
  final String message;
  const Failure(this.message);

  @override
  List<Object> get props => [message];
}

/// Server-related failures
class ServerFailure extends Failure {
  const ServerFailure(String message) : super(message);
}

/// Network-related failures
class NetworkFailure extends Failure {
  const NetworkFailure(String message) : super(message);
}

/// Cache-related failures
class CacheFailure extends Failure {
  const CacheFailure(String message) : super(message);
}

/// Authentication-related failures
class AuthFailure extends Failure {
  const AuthFailure(String message) : super(message);
}

/// Input validation failures
class ValidationFailure extends Failure {
  const ValidationFailure(String message) : super(message);
}

/// Not found failures
class NotFoundFailure extends Failure {
  const NotFoundFailure(String message) : super(message);
}

/// Permission-related failures
class PermissionFailure extends Failure {
  const PermissionFailure(String message) : super(message);
}

/// General unexpected failures
class GeneralFailure extends Failure {
  const GeneralFailure({String message = 'An unexpected error occurred'})
    : super(message);
}

</file>
<file path="lib/core/network/supabase_service.dart">
import 'package:supabase_flutter/supabase_flutter.dart';
import '../config/app_config.dart';

/// Service for Supabase client access
class SupabaseService {
  static final SupabaseClient _client = Supabase.instance.client;

  /// Get the Supabase client instance
  static SupabaseClient get client => _client;

  /// Get the current user's access token
  static String? get accessToken => _client.auth.currentSession?.accessToken;

  /// Get the current user
  static User? get currentUser => _client.auth.currentUser;

  /// Helper for authenticated queries
  static SupabaseQueryBuilder authenticatedQuery(String table) {
    return _client.from(table);
  }

  /// Initialize Supabase client
  static Future<void> initialize() async {
    await Supabase.initialize(
      url: AppConfig.supabaseUrl,
      anonKey: AppConfig.supabaseAnonKey,
    );
  }

  /// Sign out the current user
  static Future<void> signOut() async {
    await _client.auth.signOut();
  }
}

</file>
<file path="lib/core/routes/app_router.dart">
import 'package:flutter/material.dart';

import '../../features/auth/presentation/pages/login_page.dart';
import '../../features/auth/presentation/pages/signup_page.dart';
import '../../features/onboarding/presentation/pages/splash_page.dart';
import '../../features/onboarding/presentation/pages/onboarding_page.dart';
import '../../features/dashboard/presentation/pages/dashboard_page.dart';
import '../../features/grocery_list/presentation/pages/grocery_list_page.dart';
import '../../features/meal_planning/presentation/pages/meal_plan_page.dart';
import '../../features/profile/presentation/pages/profile_page.dart';
import '../../features/recipes/presentation/pages/recipe_detail_page.dart';
import '../../features/budget_tracking/presentation/pages/budget_page.dart';
import '../../features/budget_tracking/presentation/pages/create_budget_page.dart';
import '../../features/budget_tracking/presentation/pages/add_expense_page.dart';
import '../../features/budget_tracking/domain/entities/budget.dart';

/// App routes manager
class AppRouter {
  /// Route names
  static const String splash = '/';
  static const String onboarding = '/onboarding';
  static const String login = '/login';
  static const String signup = '/signup';
  static const String dashboard = '/dashboard';
  static const String recipeDetail = '/recipe-detail';
  static const String mealPlan = '/meal-plan';
  static const String groceryList = '/shopping-list';
  static const String profile = '/profile';
  static const String generatePlan = '/plan/generate';
  static const String viewPlan = '/plan/view';
  static const String budget = '/budget';
  static const String createBudget = '/budget/create';
  static const String addExpense = '/budget/expense/add';

  /// Generate routes
  static Route<dynamic> generateRoute(RouteSettings settings) {
    switch (settings.name) {
      case splash:
        return MaterialPageRoute(builder: (_) => const SplashPage());

      case onboarding:
        return MaterialPageRoute(builder: (_) => const OnboardingPage());

      case login:
        return MaterialPageRoute(builder: (_) => const LoginPage());

      case signup:
        return MaterialPageRoute(builder: (_) => const SignupPage());

      case dashboard:
        return MaterialPageRoute(builder: (_) => const DashboardPage());

      case recipeDetail:
        final args = settings.arguments as Map<String, dynamic>;
        final recipeId = args['recipeId'] as String;

        return MaterialPageRoute(
          builder: (_) => RecipeDetailPage(recipeId: recipeId),
        );

      case budget:
        return MaterialPageRoute(builder: (_) => const BudgetPage());

      case createBudget:
        return MaterialPageRoute(builder: (_) => const CreateBudgetPage());

      case addExpense:
        final args = settings.arguments as Map<String, dynamic>;
        final budget = args['budget'] as Budget;
        final initialCategory = args['initialCategory'] as BudgetCategory?;

        return MaterialPageRoute(
          builder: (_) =>
              AddExpensePage(budget: budget, initialCategory: initialCategory),
        );

      default:
        return MaterialPageRoute(
          builder: (_) => Scaffold(
            body: Center(child: Text('No route defined for ${settings.name}')),
          ),
        );
    }
  }
}

</file>
<file path="lib/core/theme/app_theme.dart">
import 'package:flutter/material.dart';

/// Application theme based on the Foodster design system
class AppTheme {
  // Base colors
  static const Color darkNavy = Color(0xFF1A1A2E);
  static const Color mediumGray = Color(0xFF8F90A6);
  static const Color lightGray = Color(0xFFE4E4EB);

  // Primary Colors
  static const Color primaryOrange = Color(0xFFFF6B35);
  static const Color primaryCoral = Color(0xFFFF8A65);
  static const Color primaryPeach = Color(0xFFFFB74D);
  static const Color primaryGreen = Color(0xFF4CAF50);
  static const Color primaryBlue = Color(0xFF2196F3);

  // Accent Colors
  static const Color accentYellow = Color(0xFFFFC107);
  static const Color accentPink = Color(0xFFE91E63);
  static const Color accentPurple = Color(0xFF9C27B0);
  static const Color accentBlue = Color(0xFF2196F3);

  // Error/Success Colors
  static const Color errorRed = Color(0xFFE53935);
  static const Color successGreen = Color(0xFF43A047);

  // Neutral Colors
  static const Color white = Color(0xFFFFFFFF);
  static const Color slate = Color(0xFF2D3748);
  static const Color charcoal = Color(0xFF16213E);
  static const Color darkGray = Color(0xFF424242);

  // Gradient Definitions
  static const LinearGradient primaryGradient = LinearGradient(
    begin: Alignment.topLeft,
    end: Alignment.bottomRight,
    colors: [primaryOrange, primaryPeach],
  );

  static const LinearGradient darkGradient = LinearGradient(
    begin: Alignment.topLeft,
    end: Alignment.bottomRight,
    colors: [darkNavy, slate],
  );

  static const LinearGradient accentGradient = LinearGradient(
    begin: Alignment.topLeft,
    end: Alignment.bottomRight,
    colors: [accentPink, accentPurple],
  );

  // Typography
  static const String fontFamily = 'SF Pro Display';

  static const TextStyle h1 = TextStyle(
    fontFamily: fontFamily,
    fontSize: 30,
    fontWeight: FontWeight.w700,
    height: 1.2,
    color: white,
  );

  static const TextStyle h2 = TextStyle(
    fontFamily: fontFamily,
    fontSize: 24,
    fontWeight: FontWeight.w600,
    height: 1.3,
    color: white,
  );

  static const TextStyle h3 = TextStyle(
    fontFamily: fontFamily,
    fontSize: 20,
    fontWeight: FontWeight.w600,
    height: 1.4,
    color: white,
  );

  static const TextStyle body = TextStyle(
    fontFamily: fontFamily,
    fontSize: 16,
    fontWeight: FontWeight.w400,
    height: 1.5,
    color: mediumGray,
  );

  static final TextStyle bodyMuted = body.copyWith(
    color: mediumGray.withOpacity(0.7),
  );

  static const TextStyle bodySmall = TextStyle(
    fontFamily: fontFamily,
    fontSize: 14,
    fontWeight: FontWeight.w400,
    height: 1.4,
    color: mediumGray,
  );

  static const TextStyle button = TextStyle(
    fontFamily: fontFamily,
    fontSize: 16,
    fontWeight: FontWeight.w600,
    height: 1.2,
    color: white,
  );

  static const TextStyle caption = TextStyle(
    fontFamily: fontFamily,
    fontSize: 14,
    fontWeight: FontWeight.w400,
    height: 1.4,
    color: mediumGray,
  );

  // Spacing
  static const double spacing1 = 4;
  static const double spacing2 = 8;
  static const double spacing3 = 12;
  static const double spacing4 = 16;
  static const double spacing5 = 24;
  static const double spacing6 = 32;
  static const double spacing7 = 48;
  static const double spacing8 = 64;

  // Border Radius
  static final BorderRadius borderRadius = BorderRadius.circular(8);
  static final BorderRadius borderRadiusLarge = BorderRadius.circular(16);
  static final BorderRadius borderRadiusSmall = BorderRadius.circular(4);

  // Card Elevation
  static const double cardElevation = 2;
  static const double modalElevation = 8;

  // Shadows
  static const BoxShadow cardShadow = BoxShadow(
    offset: Offset(0, 4),
    blurRadius: 20,
    color: Color(0x14000000), // 0.08 opacity
  );

  static const BoxShadow buttonShadow = BoxShadow(
    offset: Offset(0, 4),
    blurRadius: 12,
    color: Color(0x4DFF6B35), // 0.3 opacity of primaryOrange
  );

  // ThemeData
  static ThemeData get lightTheme {
    return ThemeData(
      primaryColor: primaryOrange,
      colorScheme: ColorScheme.light(
        primary: primaryOrange,
        secondary: accentYellow,
        error: errorRed,
      ),
      // ...rest of existing theme data...
    );
  }

  static ThemeData get darkTheme {
    return ThemeData.dark().copyWith(
      primaryColor: primaryOrange,
      colorScheme: ColorScheme.dark(
        primary: primaryOrange,
        secondary: accentYellow,
        error: errorRed,
        background: darkNavy,
      ),
      scaffoldBackgroundColor: darkNavy,
      appBarTheme: const AppBarTheme(
        backgroundColor: Colors.transparent,
        elevation: 0,
        centerTitle: true,
        titleTextStyle: h3,
        iconTheme: IconThemeData(color: white),
      ),
      bottomNavigationBarTheme: const BottomNavigationBarThemeData(
        backgroundColor: darkNavy,
        selectedItemColor: primaryOrange,
        unselectedItemColor: mediumGray,
        elevation: 8.0,
        type: BottomNavigationBarType.fixed,
      ),
      textTheme: const TextTheme(
        displayLarge: h1,
        displayMedium: h2,
        displaySmall: h3,
        bodyLarge: body,
        bodyMedium: caption,
        labelLarge: button,
      ),
      inputDecorationTheme: InputDecorationTheme(
        filled: true,
        fillColor: slate,
        border: OutlineInputBorder(
          borderRadius: BorderRadius.circular(12),
          borderSide: BorderSide.none,
        ),
        enabledBorder: OutlineInputBorder(
          borderRadius: BorderRadius.circular(12),
          borderSide: BorderSide.none,
        ),
        focusedBorder: OutlineInputBorder(
          borderRadius: BorderRadius.circular(12),
          borderSide: const BorderSide(color: primaryOrange, width: 2),
        ),
        errorBorder: OutlineInputBorder(
          borderRadius: BorderRadius.circular(12),
          borderSide: const BorderSide(color: accentPink, width: 2),
        ),
        contentPadding: const EdgeInsets.all(16),
        hintStyle: body.copyWith(color: mediumGray.withOpacity(0.7)),
      ),
      elevatedButtonTheme: ElevatedButtonThemeData(
        style: ButtonStyle(
          backgroundColor: MaterialStateProperty.all<Color>(primaryOrange),
          foregroundColor: MaterialStateProperty.all<Color>(white),
          textStyle: MaterialStateProperty.all<TextStyle>(button),
          padding: MaterialStateProperty.all<EdgeInsets>(
            const EdgeInsets.symmetric(vertical: 16),
          ),
          shape: MaterialStateProperty.all<RoundedRectangleBorder>(
            RoundedRectangleBorder(borderRadius: BorderRadius.circular(25)),
          ),
          elevation: MaterialStateProperty.all<double>(0),
          shadowColor: MaterialStateProperty.all<Color>(Colors.transparent),
        ),
      ),
      outlinedButtonTheme: OutlinedButtonThemeData(
        style: ButtonStyle(
          backgroundColor: MaterialStateProperty.all<Color>(Colors.transparent),
          foregroundColor: MaterialStateProperty.all<Color>(primaryOrange),
          textStyle: MaterialStateProperty.all<TextStyle>(button),
          padding: MaterialStateProperty.all<EdgeInsets>(
            const EdgeInsets.symmetric(vertical: 16),
          ),
          side: MaterialStateProperty.all<BorderSide>(
            const BorderSide(color: primaryOrange, width: 2),
          ),
          shape: MaterialStateProperty.all<RoundedRectangleBorder>(
            RoundedRectangleBorder(borderRadius: BorderRadius.circular(25)),
          ),
        ),
      ),
      textButtonTheme: TextButtonThemeData(
        style: ButtonStyle(
          foregroundColor: MaterialStateProperty.all<Color>(primaryOrange),
          textStyle: MaterialStateProperty.all<TextStyle>(button),
          padding: MaterialStateProperty.all<EdgeInsets>(
            const EdgeInsets.symmetric(vertical: 8, horizontal: 16),
          ),
        ),
      ),
      cardTheme: CardThemeData(
        color: charcoal,
        shadowColor: Colors.black.withOpacity(0.2),
        elevation: 5,
        shape: RoundedRectangleBorder(borderRadius: BorderRadius.circular(20)),
        margin: const EdgeInsets.symmetric(vertical: 8, horizontal: 0),
      ),
      dividerTheme: const DividerThemeData(
        color: slate,
        thickness: 1,
        indent: 16,
        endIndent: 16,
      ),
      iconTheme: const IconThemeData(color: white, size: 24),
      progressIndicatorTheme: const ProgressIndicatorThemeData(
        color: primaryOrange,
      ),
      tooltipTheme: TooltipThemeData(
        decoration: BoxDecoration(
          color: slate.withOpacity(0.9),
          borderRadius: BorderRadius.circular(8),
        ),
        textStyle: caption.copyWith(color: white),
      ),
      snackBarTheme: SnackBarThemeData(
        backgroundColor: charcoal,
        contentTextStyle: body.copyWith(color: white),
        shape: RoundedRectangleBorder(borderRadius: BorderRadius.circular(12)),
        behavior: SnackBarBehavior.floating,
      ),
      radioTheme: RadioThemeData(
        fillColor: MaterialStateProperty.all<Color>(primaryOrange),
      ),
      checkboxTheme: CheckboxThemeData(
        fillColor: MaterialStateProperty.all<Color>(primaryOrange),
        shape: RoundedRectangleBorder(borderRadius: BorderRadius.circular(4)),
      ),
      switchTheme: SwitchThemeData(
        thumbColor: MaterialStateProperty.resolveWith<Color>((states) {
          if (states.contains(MaterialState.selected)) {
            return primaryOrange;
          }
          return white;
        }),
        trackColor: MaterialStateProperty.resolveWith<Color>((states) {
          if (states.contains(MaterialState.selected)) {
            return primaryOrange.withOpacity(0.5);
          }
          return mediumGray.withOpacity(0.5);
        }),
      ),
      sliderTheme: SliderThemeData(
        activeTrackColor: primaryOrange,
        inactiveTrackColor: mediumGray.withOpacity(0.3),
        thumbColor: primaryOrange,
        overlayColor: primaryOrange.withOpacity(0.3),
        valueIndicatorColor: primaryOrange,
        valueIndicatorTextStyle: caption.copyWith(color: white),
      ),
      chipTheme: ChipThemeData(
        backgroundColor: slate,
        disabledColor: slate.withOpacity(0.5),
        selectedColor: primaryOrange,
        secondarySelectedColor: primaryOrange,
        padding: const EdgeInsets.symmetric(horizontal: 12, vertical: 8),
        labelStyle: caption.copyWith(color: white),
        secondaryLabelStyle: caption.copyWith(color: white),
        brightness: Brightness.dark,
        shape: RoundedRectangleBorder(borderRadius: BorderRadius.circular(25)),
      ),
      tabBarTheme: const TabBarThemeData(
        labelColor: primaryOrange,
        unselectedLabelColor: mediumGray,
        indicatorSize: TabBarIndicatorSize.tab,
        indicator: UnderlineTabIndicator(
          borderSide: BorderSide(color: primaryOrange, width: 2),
        ),
      ),
      dialogTheme: DialogThemeData(
        backgroundColor: charcoal,
        shape: RoundedRectangleBorder(borderRadius: BorderRadius.circular(20)),
        titleTextStyle: h3,
        contentTextStyle: body,
      ),
    );
  }
}

</file>
<file path="lib/core/usecases/usecase.dart">
import 'package:dartz/dartz.dart';
import 'package:equatable/equatable.dart';

import '../error/failure.dart';

/// Abstract base class for all usecases in the app
abstract class UseCase<Type, Params> {
  Future<Either<Failure, Type>> call(Params params);
}

/// Represents empty parameters when no parameters are needed
class NoParams extends Equatable {
  @override
  List<Object> get props => [];
}

</file>
<file path="lib/core/widgets/keyboard_dismissible.dart">
import 'package:flutter/material.dart';

/// A widget that dismisses the keyboard when tapping outside of input fields.
/// Wrap your page content with this widget to automatically handle keyboard dismissal.
class KeyboardDismissible extends StatelessWidget {
  final Widget child;

  const KeyboardDismissible({super.key, required this.child});

  @override
  Widget build(BuildContext context) {
    return GestureDetector(
      behavior: HitTestBehavior.opaque,
      onTap: () {
        // Find the primary focus scope and unfocus it
        final FocusScopeNode focusScope = FocusScope.of(context);
        if (!focusScope.hasPrimaryFocus && focusScope.hasFocus) {
          FocusManager.instance.primaryFocus?.unfocus();
        }
      },
      child: child,
    );
  }
}

</file>
<file path="lib/core/widgets/loading_indicator.dart">
import 'package:flutter/material.dart';

/// A loading indicator widget that shows a centered circular progress indicator
class LoadingIndicator extends StatelessWidget {
  /// Creates a loading indicator
  const LoadingIndicator({super.key});

  @override
  Widget build(BuildContext context) {
    return const CircularProgressIndicator(
      valueColor: AlwaysStoppedAnimation<Color>(Colors.white),
    );
  }
}

</file>
<file path="lib/features/auth/presentation/pages/login_page.dart">
import 'package:flutter/material.dart';
import '../../../../core/constants/message_constants.dart';
import '../../../../core/theme/app_theme.dart';

/// Login page for user authentication
class LoginPage extends StatefulWidget {
  const LoginPage({super.key});

  @override
  State<LoginPage> createState() => _LoginPageState();
}

class _LoginPageState extends State<LoginPage> {
  final _formKey = GlobalKey<FormState>();
  final _emailController = TextEditingController();
  final _passwordController = TextEditingController();
  bool _isLoading = false;
  bool _obscurePassword = true;

  @override
  void dispose() {
    _emailController.dispose();
    _passwordController.dispose();
    super.dispose();
  }

  String? _validateEmail(String? value) {
    if (value == null || value.isEmpty) {
      return ValidationErrors.emptyField;
    }
    final emailRegex = RegExp(r'^[^@]+@[^@]+\.[^@]+');
    if (!emailRegex.hasMatch(value)) {
      return ValidationErrors.invalidEmail;
    }
    return null;
  }

  String? _validatePassword(String? value) {
    if (value == null || value.isEmpty) {
      return ValidationErrors.emptyField;
    }
    if (value.length < 8) {
      return ValidationErrors.passwordTooShort;
    }
    return null;
  }

  void _togglePasswordVisibility() {
    setState(() {
      _obscurePassword = !_obscurePassword;
    });
  }

  Future<void> _login() async {
    if (_formKey.currentState?.validate() == true) {
      setState(() {
        _isLoading = true;
      });

      try {
        // TODO: Implement actual login with Supabase
        await Future.delayed(
          const Duration(seconds: 2),
        ); // Simulate network request

        if (context.mounted) {
          // Navigate to dashboard on success
          Navigator.of(context).pushReplacementNamed('/dashboard');
        }
      } catch (e) {
        if (context.mounted) {
          ScaffoldMessenger.of(context).showSnackBar(
            SnackBar(content: Text(e.toString()), backgroundColor: Colors.red),
          );
        }
      } finally {
        if (mounted) {
          setState(() {
            _isLoading = false;
          });
        }
      }
    }
  }

  @override
  Widget build(BuildContext context) {
    return Scaffold(
      backgroundColor: AppTheme.darkNavy,
      appBar: AppBar(title: const Text('Sign In')),
      body: SingleChildScrollView(
        child: Padding(
          padding: const EdgeInsets.all(24),
          child: Form(
            key: _formKey,
            child: Column(
              crossAxisAlignment: CrossAxisAlignment.stretch,
              children: [
                const SizedBox(height: 32),
                const Text('Welcome back', style: AppTheme.h1),
                const SizedBox(height: 16),
                const Text(
                  'Sign in to continue with your meal planning journey',
                  style: AppTheme.body,
                ),
                const SizedBox(height: 48),
                TextFormField(
                  controller: _emailController,
                  keyboardType: TextInputType.emailAddress,
                  decoration: const InputDecoration(
                    labelText: 'Email',
                    prefixIcon: Icon(Icons.email_outlined),
                  ),
                  validator: _validateEmail,
                ),
                const SizedBox(height: 24),
                TextFormField(
                  controller: _passwordController,
                  obscureText: _obscurePassword,
                  decoration: InputDecoration(
                    labelText: 'Password',
                    prefixIcon: const Icon(Icons.lock_outline),
                    suffixIcon: IconButton(
                      icon: Icon(
                        _obscurePassword
                            ? Icons.visibility_off
                            : Icons.visibility,
                      ),
                      onPressed: _togglePasswordVisibility,
                    ),
                  ),
                  validator: _validatePassword,
                ),
                const SizedBox(height: 16),
                Align(
                  alignment: Alignment.centerRight,
                  child: TextButton(
                    onPressed: () {
                      // TODO: Implement forgot password
                    },
                    child: const Text('Forgot Password?'),
                  ),
                ),
                const SizedBox(height: 32),
                SizedBox(
                  height: 55,
                  child: ElevatedButton(
                    onPressed: _isLoading ? null : _login,
                    child: _isLoading
                        ? const SizedBox(
                            width: 24,
                            height: 24,
                            child: CircularProgressIndicator(
                              strokeWidth: 2.5,
                              color: Colors.white,
                            ),
                          )
                        : const Text('Sign In'),
                  ),
                ),
                const SizedBox(height: 24),
                Row(
                  mainAxisAlignment: MainAxisAlignment.center,
                  children: [
                    const Text(
                      "Don't have an account?",
                      style: AppTheme.caption,
                    ),
                    TextButton(
                      onPressed: () {
                        Navigator.of(context).pushReplacementNamed('/signup');
                      },
                      child: const Text('Create Account'),
                    ),
                  ],
                ),
              ],
            ),
          ),
        ),
      ),
    );
  }
}

</file>
<file path="lib/features/auth/presentation/pages/signup_page.dart">
import 'package:flutter/material.dart';
import '../../../../core/constants/message_constants.dart';
import '../../../../core/theme/app_theme.dart';

/// Sign up page for new user registration
class SignupPage extends StatefulWidget {
  const SignupPage({super.key});

  @override
  State<SignupPage> createState() => _SignupPageState();
}

class _SignupPageState extends State<SignupPage> {
  final _formKey = GlobalKey<FormState>();
  final _nameController = TextEditingController();
  final _emailController = TextEditingController();
  final _passwordController = TextEditingController();
  final _confirmPasswordController = TextEditingController();
  bool _isLoading = false;
  bool _obscurePassword = true;
  bool _obscureConfirmPassword = true;

  @override
  void dispose() {
    _nameController.dispose();
    _emailController.dispose();
    _passwordController.dispose();
    _confirmPasswordController.dispose();
    super.dispose();
  }

  String? _validateName(String? value) {
    if (value == null || value.isEmpty) {
      return ValidationErrors.emptyField;
    }
    if (value.length < 2) {
      return ValidationErrors.invalidName;
    }
    return null;
  }

  String? _validateEmail(String? value) {
    if (value == null || value.isEmpty) {
      return ValidationErrors.emptyField;
    }
    final emailRegex = RegExp(r'^[^@]+@[^@]+\.[^@]+');
    if (!emailRegex.hasMatch(value)) {
      return ValidationErrors.invalidEmail;
    }
    return null;
  }

  String? _validatePassword(String? value) {
    if (value == null || value.isEmpty) {
      return ValidationErrors.emptyField;
    }
    if (value.length < 8) {
      return ValidationErrors.passwordTooShort;
    }
    return null;
  }

  String? _validateConfirmPassword(String? value) {
    if (value == null || value.isEmpty) {
      return ValidationErrors.emptyField;
    }
    if (value != _passwordController.text) {
      return ValidationErrors.passwordsDoNotMatch;
    }
    return null;
  }

  void _togglePasswordVisibility() {
    setState(() {
      _obscurePassword = !_obscurePassword;
    });
  }

  void _toggleConfirmPasswordVisibility() {
    setState(() {
      _obscureConfirmPassword = !_obscureConfirmPassword;
    });
  }

  Future<void> _signUp() async {
    if (_formKey.currentState?.validate() == true) {
      setState(() {
        _isLoading = true;
      });

      try {
        // TODO: Implement actual signup with Supabase
        await Future.delayed(
          const Duration(seconds: 2),
        ); // Simulate network request

        if (context.mounted) {
          // Navigate to onboarding preference flow
          Navigator.of(context).pushReplacementNamed('/onboarding');
        }
      } catch (e) {
        if (context.mounted) {
          ScaffoldMessenger.of(context).showSnackBar(
            SnackBar(content: Text(e.toString()), backgroundColor: Colors.red),
          );
        }
      } finally {
        if (mounted) {
          setState(() {
            _isLoading = false;
          });
        }
      }
    }
  }

  @override
  Widget build(BuildContext context) {
    return Scaffold(
      backgroundColor: AppTheme.darkNavy,
      appBar: AppBar(title: const Text('Create Account')),
      body: SingleChildScrollView(
        child: Padding(
          padding: const EdgeInsets.all(24),
          child: Form(
            key: _formKey,
            child: Column(
              crossAxisAlignment: CrossAxisAlignment.stretch,
              children: [
                const SizedBox(height: 16),
                const Text('Join Foodster', style: AppTheme.h1),
                const SizedBox(height: 16),
                const Text(
                  'Create an account to start your personalized meal planning',
                  style: AppTheme.body,
                ),
                const SizedBox(height: 40),
                TextFormField(
                  controller: _nameController,
                  decoration: const InputDecoration(
                    labelText: 'Full Name',
                    prefixIcon: Icon(Icons.person_outline),
                  ),
                  validator: _validateName,
                ),
                const SizedBox(height: 24),
                TextFormField(
                  controller: _emailController,
                  keyboardType: TextInputType.emailAddress,
                  decoration: const InputDecoration(
                    labelText: 'Email',
                    prefixIcon: Icon(Icons.email_outlined),
                  ),
                  validator: _validateEmail,
                ),
                const SizedBox(height: 24),
                TextFormField(
                  controller: _passwordController,
                  obscureText: _obscurePassword,
                  decoration: InputDecoration(
                    labelText: 'Password',
                    prefixIcon: const Icon(Icons.lock_outline),
                    suffixIcon: IconButton(
                      icon: Icon(
                        _obscurePassword
                            ? Icons.visibility_off
                            : Icons.visibility,
                      ),
                      onPressed: _togglePasswordVisibility,
                    ),
                  ),
                  validator: _validatePassword,
                ),
                const SizedBox(height: 24),
                TextFormField(
                  controller: _confirmPasswordController,
                  obscureText: _obscureConfirmPassword,
                  decoration: InputDecoration(
                    labelText: 'Confirm Password',
                    prefixIcon: const Icon(Icons.lock_outline),
                    suffixIcon: IconButton(
                      icon: Icon(
                        _obscureConfirmPassword
                            ? Icons.visibility_off
                            : Icons.visibility,
                      ),
                      onPressed: _toggleConfirmPasswordVisibility,
                    ),
                  ),
                  validator: _validateConfirmPassword,
                ),
                const SizedBox(height: 32),
                SizedBox(
                  height: 55,
                  child: ElevatedButton(
                    onPressed: _isLoading ? null : _signUp,
                    child: _isLoading
                        ? const SizedBox(
                            width: 24,
                            height: 24,
                            child: CircularProgressIndicator(
                              strokeWidth: 2.5,
                              color: Colors.white,
                            ),
                          )
                        : const Text('Create Account'),
                  ),
                ),
                const SizedBox(height: 24),
                Row(
                  mainAxisAlignment: MainAxisAlignment.center,
                  children: [
                    const Text(
                      'Already have an account?',
                      style: AppTheme.caption,
                    ),
                    TextButton(
                      onPressed: () {
                        Navigator.of(context).pushReplacementNamed('/login');
                      },
                      child: const Text('Sign In'),
                    ),
                  ],
                ),
              ],
            ),
          ),
        ),
      ),
    );
  }
}

</file>
<file path="lib/features/auth/presentation/pages/splash_page.dart">
import 'package:flutter/material.dart';
import 'package:flutter_bloc/flutter_bloc.dart';
import '../../../../core/theme/app_theme.dart';
import '../../../../core/config/app_config.dart';
import '../../../../core/routes/app_router.dart';
import '../widgets/onboarding_slide.dart';
import 'login_page.dart';
import 'signup_page.dart';

/// Initial application screen for onboarding and welcome
class SplashPage extends StatefulWidget {
  const SplashPage({super.key});

  @override
  State<SplashPage> createState() => _SplashPageState();
}

class _SplashPageState extends State<SplashPage> {
  @override
  void initState() {
    super.initState();
    // Add temporary auth bypass
    if (AppConfig.skipAuth) {
      WidgetsBinding.instance.addPostFrameCallback((_) {
        Navigator.of(context).pushReplacementNamed(AppRouter.dashboard);
      });
    }
  }

  final PageController _pageController = PageController();
  int _currentPage = 0;

  final List<OnboardingSlideData> _slides = [
    OnboardingSlideData(
      title: 'Welcome to Foodster',
      description: 'Your smart nutrition and meal planning companion',
      image: 'assets/images/onboarding_1.png',
    ),
    OnboardingSlideData(
      title: 'Smart Meal Planning',
      description:
          'Generate personalized meal plans based on your preferences and dietary needs',
      image: 'assets/images/onboarding_2.png',
    ),
    OnboardingSlideData(
      title: 'Budget Optimization',
      description: 'Track grocery spending and find the best deals near you',
      image: 'assets/images/onboarding_3.png',
    ),
  ];

  @override
  void dispose() {
    _pageController.dispose();
    super.dispose();
  }

  void _onPageChanged(int page) {
    setState(() {
      _currentPage = page;
    });
  }

  void _navigateToLogin() {
    Navigator.of(
      context,
    ).push(MaterialPageRoute(builder: (context) => const LoginPage()));
  }

  void _navigateToSignup() {
    Navigator.of(
      context,
    ).push(MaterialPageRoute(builder: (context) => const SignupPage()));
  }

  @override
  Widget build(BuildContext context) {
    return Scaffold(
      backgroundColor: AppTheme.darkNavy,
      body: SafeArea(
        child: Column(
          children: [
            Expanded(
              flex: 4,
              child: PageView.builder(
                controller: _pageController,
                itemCount: _slides.length,
                onPageChanged: _onPageChanged,
                itemBuilder: (context, index) {
                  return OnboardingSlide(data: _slides[index]);
                },
              ),
            ),
            Expanded(
              flex: 1,
              child: Column(
                mainAxisAlignment: MainAxisAlignment.center,
                children: [
                  // Page indicator
                  Row(
                    mainAxisAlignment: MainAxisAlignment.center,
                    children: List.generate(
                      _slides.length,
                      (index) => AnimatedContainer(
                        duration: const Duration(milliseconds: 300),
                        margin: const EdgeInsets.symmetric(horizontal: 4),
                        height: 8,
                        width: index == _currentPage ? 24 : 8,
                        decoration: BoxDecoration(
                          color: index == _currentPage
                              ? AppTheme.primaryOrange
                              : AppTheme.mediumGray.withOpacity(0.5),
                          borderRadius: BorderRadius.circular(4),
                        ),
                      ),
                    ),
                  ),
                  const SizedBox(height: 40),
                  // Action buttons
                  Padding(
                    padding: const EdgeInsets.symmetric(horizontal: 24.0),
                    child: Row(
                      children: [
                        Expanded(
                          child: ElevatedButton(
                            onPressed: _navigateToLogin,
                            child: const Text('Sign In'),
                          ),
                        ),
                        const SizedBox(width: 16),
                        Expanded(
                          child: OutlinedButton(
                            onPressed: _navigateToSignup,
                            child: const Text('Create Account'),
                          ),
                        ),
                      ],
                    ),
                  ),
                ],
              ),
            ),
          ],
        ),
      ),
    );
  }
}

</file>
<file path="lib/features/auth/presentation/widgets/onboarding_slide.dart">
import 'package:flutter/material.dart';
import '../../../../core/theme/app_theme.dart';

/// Data class for onboarding slide content
class OnboardingSlideData {
  final String title;
  final String description;
  final String image;

  OnboardingSlideData({
    required this.title,
    required this.description,
    required this.image,
  });
}

/// Widget that displays an onboarding slide
class OnboardingSlide extends StatelessWidget {
  final OnboardingSlideData data;

  const OnboardingSlide({super.key, required this.data});

  @override
  Widget build(BuildContext context) {
    return Padding(
      padding: const EdgeInsets.all(24),
      child: Column(
        mainAxisAlignment: MainAxisAlignment.center,
        children: [
          // Image placeholder - in a real app, you'd use the actual image
          Container(
            height: 240,
            width: double.infinity,
            decoration: BoxDecoration(
              color: AppTheme.slate.withOpacity(0.5),
              borderRadius: BorderRadius.circular(20),
            ),
            child: Center(
              child: Icon(Icons.image, color: AppTheme.mediumGray, size: 80),
            ),
          ),
          const SizedBox(height: 40),
          Text(data.title, style: AppTheme.h2, textAlign: TextAlign.center),
          const SizedBox(height: 16),
          Text(
            data.description,
            style: AppTheme.body,
            textAlign: TextAlign.center,
          ),
        ],
      ),
    );
  }
}

</file>
<file path="lib/features/budget_tracking/data/models/budget_model.dart">
import 'package:uuid/uuid.dart';
import '../../domain/entities/budget.dart';

/// Model for budget category
class BudgetCategoryModel extends BudgetCategory {
  const BudgetCategoryModel({
    required super.id,
    required super.name,
    required super.allocation,
    required super.spent,
  });

  /// Create a copy with some fields replaced
  @override
  BudgetCategoryModel copyWith({
    String? id,
    String? name,
    double? allocation,
    double? spent,
  }) {
    return BudgetCategoryModel(
      id: id ?? this.id,
      name: name ?? this.name,
      allocation: allocation ?? this.allocation,
      spent: spent ?? this.spent,
    );
  }

  /// Create a category with default allocation based on percentage
  factory BudgetCategoryModel.withDefaultAllocation(
    String name,
    double percentage,
    double totalBudget,
  ) {
    return BudgetCategoryModel(
      id: const Uuid().v4(),
      name: name,
      allocation: (percentage / 100) * totalBudget,
      spent: 0,
    );
  }

  /// Create a BudgetCategoryModel from a JSON map
  factory BudgetCategoryModel.fromJson(Map<String, dynamic> json) {
    return BudgetCategoryModel(
      id: json['id'],
      name: json['name'],
      allocation: json['allocation'].toDouble(),
      spent: json['spent'].toDouble(),
    );
  }

  /// Convert this BudgetCategoryModel to a JSON map
  Map<String, dynamic> toJson() {
    return {'id': id, 'name': name, 'allocation': allocation, 'spent': spent};
  }
}

/// Data model for budget information
class BudgetModel extends Budget {
  const BudgetModel({
    required super.id,
    required super.amount,
    required super.period,
    required super.startDate,
    required super.endDate,
    required super.spent,
    required super.remaining,
    required super.categories,
  });

  /// Create a copy with some fields replaced
  @override
  BudgetModel copyWith({
    String? id,
    double? amount,
    String? period,
    DateTime? startDate,
    DateTime? endDate,
    double? spent,
    double? remaining,
    List<BudgetCategory>? categories,
  }) {
    return BudgetModel(
      id: id ?? this.id,
      amount: amount ?? this.amount,
      period: period ?? this.period,
      startDate: startDate ?? this.startDate,
      endDate: endDate ?? this.endDate,
      spent: spent ?? this.spent,
      remaining: remaining ?? this.remaining,
      categories: categories ?? this.categories,
    );
  }

  /// Create a BudgetModel from a JSON map
  factory BudgetModel.fromJson(Map<String, dynamic> json) {
    return BudgetModel(
      id: json['id'],
      amount: json['amount'].toDouble(),
      period: json['period'],
      startDate: DateTime.parse(json['startDate']),
      endDate: DateTime.parse(json['endDate']),
      spent: json['spent'].toDouble(),
      remaining: json['remaining'].toDouble(),
      categories: (json['categories'] as List)
          .map((category) => BudgetCategoryModel.fromJson(category))
          .toList(),
    );
  }

  /// Convert this BudgetModel to a JSON map
  Map<String, dynamic> toJson() {
    return {
      'id': id,
      'amount': amount,
      'period': period,
      'startDate': startDate.toIso8601String(),
      'endDate': endDate.toIso8601String(),
      'spent': spent,
      'remaining': remaining,
      'categories': categories.map((category) {
        if (category is BudgetCategoryModel) {
          return category.toJson();
        }
        // Convert regular BudgetCategory to BudgetCategoryModel
        return BudgetCategoryModel(
          id: category.id,
          name: category.name,
          allocation: category.allocation,
          spent: category.spent,
        ).toJson();
      }).toList(),
    };
  }

  /// Create a new budget with default categories
  static BudgetModel createNew({
    required double amount,
    required String period,
  }) {
    final now = DateTime.now();
    final endDate = period == 'weekly'
        ? now.add(const Duration(days: 7))
        : DateTime(now.year, now.month + 1, now.day);

    // Default budget categories with standard allocation percentages
    final categories = [
      BudgetCategoryModel.withDefaultAllocation('Produce', 30, amount),
      BudgetCategoryModel.withDefaultAllocation('Protein', 25, amount),
      BudgetCategoryModel.withDefaultAllocation('Dairy', 15, amount),
      BudgetCategoryModel.withDefaultAllocation('Grains & Pasta', 10, amount),
      BudgetCategoryModel.withDefaultAllocation('Snacks', 10, amount),
      BudgetCategoryModel.withDefaultAllocation('Beverages', 5, amount),
      BudgetCategoryModel.withDefaultAllocation('Other', 5, amount),
    ];

    return BudgetModel(
      id: const Uuid().v4(),
      amount: amount,
      period: period,
      startDate: now,
      endDate: endDate,
      spent: 0,
      remaining: amount,
      categories: categories,
    );
  }

  /// Get mock budget data for development
  static BudgetModel getMockBudget() {
    final now = DateTime.now();
    final endDate = now.add(const Duration(days: 30));
    const totalBudget = 600.0;
    const spent = 320.0;

    return BudgetModel(
      id: 'mock-budget-1',
      amount: totalBudget,
      period: 'monthly',
      startDate: now.subtract(const Duration(days: 10)),
      endDate: endDate,
      spent: spent,
      remaining: totalBudget - spent,
      categories: [
        BudgetCategoryModel(
          id: 'cat-1',
          name: 'Produce',
          allocation: 180.0,
          spent: 95.0,
        ),
        BudgetCategoryModel(
          id: 'cat-2',
          name: 'Protein',
          allocation: 150.0,
          spent: 120.0,
        ),
        BudgetCategoryModel(
          id: 'cat-3',
          name: 'Dairy',
          allocation: 90.0,
          spent: 40.0,
        ),
        BudgetCategoryModel(
          id: 'cat-4',
          name: 'Grains & Pasta',
          allocation: 60.0,
          spent: 30.0,
        ),
        BudgetCategoryModel(
          id: 'cat-5',
          name: 'Snacks',
          allocation: 60.0,
          spent: 20.0,
        ),
        BudgetCategoryModel(
          id: 'cat-6',
          name: 'Beverages',
          allocation: 30.0,
          spent: 10.0,
        ),
        BudgetCategoryModel(
          id: 'cat-7',
          name: 'Other',
          allocation: 30.0,
          spent: 5.0,
        ),
      ],
    );
  }
}

</file>
<file path="lib/features/budget_tracking/data/repositories/budget_repository_impl.dart">
import 'dart:convert';
import 'package:dartz/dartz.dart';
import 'package:shared_preferences/shared_preferences.dart';

import '../../../../core/error/failure.dart';
import '../../domain/entities/budget.dart';
import '../../domain/repositories/budget_repository.dart';
import '../models/budget_model.dart';

/// Implementation of BudgetRepository
class BudgetRepositoryImpl implements BudgetRepository {
  // Key for storing the budget in SharedPreferences
  static const String _budgetKey = 'current_budget';

  // Cached budget to avoid unnecessary reads
  static BudgetModel? _currentBudget;

  @override
  Future<Either<Failure, Budget>> getCurrentBudget() async {
    try {
      // Return cached budget if available
      if (_currentBudget != null) {
        return Right(_currentBudget!);
      }

      // Try to load from SharedPreferences
      final prefs = await SharedPreferences.getInstance();
      final budgetJson = prefs.getString(_budgetKey);

      if (budgetJson != null) {
        _currentBudget = BudgetModel.fromJson(json.decode(budgetJson));
        return Right(_currentBudget!);
      }

      // Return mock budget if nothing is stored
      _currentBudget = BudgetModel.getMockBudget();
      return Right(_currentBudget!);
    } catch (e) {
      return Left(ServerFailure('Failed to load budget: ${e.toString()}'));
    }
  }

  @override
  Future<Either<Failure, Budget>> createBudget({
    required double amount,
    required String period,
  }) async {
    try {
      if (amount <= 0) {
        return Left(InputFailure('Budget amount must be greater than 0'));
      }

      // Create new budget with default categories
      _currentBudget = BudgetModel.createNew(amount: amount, period: period);

      // Save to SharedPreferences
      final prefs = await SharedPreferences.getInstance();
      await prefs.setString(_budgetKey, json.encode(_currentBudget!.toJson()));

      return Right(_currentBudget!);
    } catch (e) {
      return Left(ServerFailure('Failed to create budget: ${e.toString()}'));
    }
  }

  @override
  Future<Either<Failure, Budget>> updateBudgetCategories({
    required String budgetId,
    required List<BudgetCategory> categories,
  }) async {
    try {
      if (_currentBudget == null) {
        return Left(ServerFailure('No active budget found'));
      }

      if (_currentBudget!.id != budgetId) {
        return Left(ServerFailure('Budget ID does not match active budget'));
      }

      // Check that the total allocation matches the budget amount
      final totalAllocation = categories.fold<double>(
        0,
        (sum, cat) => sum + cat.allocation,
      );

      if ((totalAllocation - _currentBudget!.amount).abs() > 0.01) {
        return Left(
          InputFailure(
            'Total category allocation must equal budget amount ' +
                '(${totalAllocation.toStringAsFixed(2)} ≠ ' +
                '${_currentBudget!.amount.toStringAsFixed(2)})',
          ),
        );
      }

      // Convert categories to BudgetCategoryModel and update the budget
      final updatedCategories = categories.map((category) {
        if (category is! BudgetCategoryModel) {
          return BudgetCategoryModel(
            id: category.id,
            name: category.name,
            allocation: category.allocation,
            spent: category.spent,
          );
        }
        return category;
      }).toList();

      _currentBudget = BudgetModel(
        id: _currentBudget!.id,
        amount: _currentBudget!.amount,
        period: _currentBudget!.period,
        startDate: _currentBudget!.startDate,
        endDate: _currentBudget!.endDate,
        spent: _currentBudget!.spent,
        remaining: _currentBudget!.remaining,
        categories: updatedCategories,
      );

      // Save to SharedPreferences
      final prefs = await SharedPreferences.getInstance();
      await prefs.setString(_budgetKey, json.encode(_currentBudget!.toJson()));

      return Right(_currentBudget!);
    } catch (e) {
      return Left(
        ServerFailure('Failed to update categories: ${e.toString()}'),
      );
    }
  }

  @override
  Future<Either<Failure, Budget>> recordSpending({
    required String budgetId,
    required String categoryId,
    required double amount,
    String? note,
  }) async {
    try {
      if (_currentBudget == null) {
        return Left(ServerFailure('No active budget found'));
      }

      if (_currentBudget!.id != budgetId) {
        return Left(ServerFailure('Budget ID does not match active budget'));
      }

      if (amount <= 0) {
        return Left(InputFailure('Spending amount must be greater than 0'));
      }

      // Find the category
      final categoryIndex = _currentBudget!.categories.indexWhere(
        (cat) => cat.id == categoryId,
      );

      if (categoryIndex == -1) {
        return Left(InputFailure('Category not found'));
      }

      // Convert and update the category spent amount
      final updatedCategories = _currentBudget!.categories.map((category) {
        if (category.id == categoryId) {
          return BudgetCategoryModel(
            id: category.id,
            name: category.name,
            allocation: category.allocation,
            spent: category.spent + amount,
          );
        }
        // Keep other categories as is, but ensure they're BudgetCategoryModel
        if (category is! BudgetCategoryModel) {
          return BudgetCategoryModel(
            id: category.id,
            name: category.name,
            allocation: category.allocation,
            spent: category.spent,
          );
        }
        return category;
      }).toList();

      // Update total spent and remaining amounts
      final newSpent = _currentBudget!.spent + amount;
      final newRemaining = _currentBudget!.amount - newSpent;

      // Update the budget with a new BudgetModel instance
      _currentBudget = BudgetModel(
        id: _currentBudget!.id,
        amount: _currentBudget!.amount,
        period: _currentBudget!.period,
        startDate: _currentBudget!.startDate,
        endDate: _currentBudget!.endDate,
        spent: newSpent,
        remaining: newRemaining,
        categories: updatedCategories,
      );

      // Save to SharedPreferences
      final prefs = await SharedPreferences.getInstance();
      await prefs.setString(_budgetKey, json.encode(_currentBudget!.toJson()));

      // In a real app, we would also save the transaction details with the note

      return Right(_currentBudget!);
    } catch (e) {
      return Left(ServerFailure('Failed to record spending: ${e.toString()}'));
    }
  }
}

</file>
<file path="lib/features/budget_tracking/domain/entities/budget.dart">
import 'package:equatable/equatable.dart';

/// Budget entity for managing grocery spending
class Budget extends Equatable {
  final String id;
  final double amount;
  final String period; // 'weekly', 'monthly'
  final DateTime startDate;
  final DateTime endDate;
  final double spent;
  final double remaining;
  final List<BudgetCategory> categories;

  const Budget({
    required this.id,
    required this.amount,
    required this.period,
    required this.startDate,
    required this.endDate,
    required this.spent,
    required this.remaining,
    required this.categories,
  });

  @override
  List<Object> get props => [
    id,
    amount,
    period,
    startDate,
    endDate,
    spent,
    remaining,
    categories,
  ];

  /// Percentage spent of total budget
  double get percentageSpent => amount > 0 ? (spent / amount * 100) : 0;

  /// Percentage remaining of total budget
  double get percentageRemaining => amount > 0 ? (remaining / amount * 100) : 0;

  /// Check if budget is over limit
  bool get isOverBudget => spent > amount;

  /// Days left in current budget period
  int get daysLeft {
    final now = DateTime.now();
    return now.isAfter(endDate) ? 0 : endDate.difference(now).inDays;
  }

  /// Daily budget based on remaining amount and days
  double get dailyBudget {
    if (daysLeft <= 0) return 0;
    return remaining / daysLeft;
  }

  /// Create a copy of this budget with some fields replaced
  Budget copyWith({
    String? id,
    double? amount,
    String? period,
    DateTime? startDate,
    DateTime? endDate,
    double? spent,
    double? remaining,
    List<BudgetCategory>? categories,
  }) {
    return Budget(
      id: id ?? this.id,
      amount: amount ?? this.amount,
      period: period ?? this.period,
      startDate: startDate ?? this.startDate,
      endDate: endDate ?? this.endDate,
      spent: spent ?? this.spent,
      remaining: remaining ?? this.remaining,
      categories: categories ?? this.categories,
    );
  }
}

/// Category for budget allocation and tracking
class BudgetCategory extends Equatable {
  final String id;
  final String name;
  final double allocation;
  final double spent;

  const BudgetCategory({
    required this.id,
    required this.name,
    required this.allocation,
    required this.spent,
  });

  @override
  List<Object> get props => [id, name, allocation, spent];

  /// Percentage spent of category allocation
  double get percentageSpent => allocation > 0 ? (spent / allocation * 100) : 0;

  /// Remaining amount in this category
  double get remaining => allocation - spent;

  /// Create a copy of this category with some fields replaced
  BudgetCategory copyWith({
    String? id,
    String? name,
    double? allocation,
    double? spent,
  }) {
    return BudgetCategory(
      id: id ?? this.id,
      name: name ?? this.name,
      allocation: allocation ?? this.allocation,
      spent: spent ?? this.spent,
    );
  }
}

</file>
<file path="lib/features/budget_tracking/domain/repositories/budget_repository.dart">
import 'package:dartz/dartz.dart';

import '../../../../core/error/failure.dart';
import '../entities/budget.dart';

/// Repository for budget tracking operations
abstract class BudgetRepository {
  /// Get the current active budget
  Future<Either<Failure, Budget>> getCurrentBudget();

  /// Create a new budget
  Future<Either<Failure, Budget>> createBudget({
    required double amount,
    required String period,
  });

  /// Update budget category allocations
  Future<Either<Failure, Budget>> updateBudgetCategories({
    required String budgetId,
    required List<BudgetCategory> categories,
  });

  /// Record spending in a specific category
  Future<Either<Failure, Budget>> recordSpending({
    required String budgetId,
    required String categoryId,
    required double amount,
    String? note,
  });
}

</file>
<file path="lib/features/budget_tracking/domain/usecases/budget_usecases.dart">
import 'package:dartz/dartz.dart';
import 'package:equatable/equatable.dart';

import '../../../../core/error/failure.dart';
import '../../../../core/usecases/usecase.dart';
import '../entities/budget.dart';
import '../repositories/budget_repository.dart';

// Use cases for budget operations

/// Use case to get the current active budget
class GetCurrentBudget implements UseCase<Budget, NoParams> {
  final BudgetRepository repository;

  GetCurrentBudget(this.repository);

  @override
  Future<Either<Failure, Budget>> call(NoParams params) {
    return repository.getCurrentBudget();
  }
}

/// Use case to create a new budget
class CreateBudget implements UseCase<Budget, CreateBudgetParams> {
  final BudgetRepository repository;

  CreateBudget(this.repository);

  @override
  Future<Either<Failure, Budget>> call(CreateBudgetParams params) {
    return repository.createBudget(
      amount: params.amount,
      period: params.period,
    );
  }
}

/// Use case to update budget categories
class UpdateBudgetCategories
    implements UseCase<Budget, UpdateCategoriesParams> {
  final BudgetRepository repository;

  UpdateBudgetCategories(this.repository);

  @override
  Future<Either<Failure, Budget>> call(UpdateCategoriesParams params) {
    return repository.updateBudgetCategories(
      budgetId: params.budgetId,
      categories: params.categories,
    );
  }
}

/// Use case to record spending in a budget category
class RecordSpending implements UseCase<Budget, RecordSpendingParams> {
  final BudgetRepository repository;

  RecordSpending(this.repository);

  @override
  Future<Either<Failure, Budget>> call(RecordSpendingParams params) {
    return repository.recordSpending(
      budgetId: params.budgetId,
      categoryId: params.categoryId,
      amount: params.amount,
      note: params.note,
    );
  }
}

/// Parameters for creating a budget
class CreateBudgetParams extends Equatable {
  final double amount;
  final String period;

  const CreateBudgetParams({required this.amount, required this.period});

  @override
  List<Object> get props => [amount, period];
}

/// Parameters for updating budget categories
class UpdateCategoriesParams extends Equatable {
  final String budgetId;
  final List<BudgetCategory> categories;

  const UpdateCategoriesParams({
    required this.budgetId,
    required this.categories,
  });

  @override
  List<Object> get props => [budgetId, categories];
}

/// Parameters for recording spending
class RecordSpendingParams extends Equatable {
  final String budgetId;
  final String categoryId;
  final double amount;
  final String? note;

  const RecordSpendingParams({
    required this.budgetId,
    required this.categoryId,
    required this.amount,
    this.note,
  });

  @override
  List<Object?> get props => [budgetId, categoryId, amount, note];
}

</file>
<file path="lib/features/budget_tracking/presentation/bloc/budget_bloc.dart">
import 'package:equatable/equatable.dart';
import 'package:flutter_bloc/flutter_bloc.dart';

import '../../../../core/usecases/usecase.dart';
import '../../domain/entities/budget.dart';
import '../../domain/usecases/budget_usecases.dart';

part 'budget_event.dart';
part 'budget_state.dart';

/// BLoC for budget tracking
class BudgetBloc extends Bloc<BudgetEvent, BudgetState> {
  final GetCurrentBudget getCurrentBudget;
  final CreateBudget createBudget;
  final UpdateBudgetCategories updateBudgetCategories;
  final RecordSpending recordSpending;

  BudgetBloc({
    required this.getCurrentBudget,
    required this.createBudget,
    required this.updateBudgetCategories,
    required this.recordSpending,
  }) : super(BudgetInitial()) {
    on<LoadBudget>(_onLoadBudget);
    on<CreateNewBudget>(_onCreateNewBudget);
    on<UpdateCategories>(_onUpdateCategories);
    on<AddExpense>(_onAddExpense);
  }

  Future<void> _onLoadBudget(
    LoadBudget event,
    Emitter<BudgetState> emit,
  ) async {
    emit(BudgetLoading());

    final result = await getCurrentBudget(NoParams());

    result.fold(
      (failure) => emit(BudgetError(message: failure.message)),
      (budget) => emit(BudgetLoaded(budget: budget)),
    );
  }

  Future<void> _onCreateNewBudget(
    CreateNewBudget event,
    Emitter<BudgetState> emit,
  ) async {
    emit(BudgetLoading());

    final result = await createBudget(
      CreateBudgetParams(amount: event.amount, period: event.period),
    );

    result.fold(
      (failure) => emit(BudgetError(message: failure.message)),
      (budget) => emit(BudgetLoaded(budget: budget)),
    );
  }

  Future<void> _onUpdateCategories(
    UpdateCategories event,
    Emitter<BudgetState> emit,
  ) async {
    final currentState = state;

    if (currentState is BudgetLoaded) {
      emit(BudgetUpdating(budget: currentState.budget));

      final result = await updateBudgetCategories(
        UpdateCategoriesParams(
          budgetId: event.budgetId,
          categories: event.categories,
        ),
      );

      result.fold(
        (failure) => emit(
          BudgetError(message: failure.message, budget: currentState.budget),
        ),
        (budget) => emit(BudgetLoaded(budget: budget)),
      );
    }
  }

  Future<void> _onAddExpense(
    AddExpense event,
    Emitter<BudgetState> emit,
  ) async {
    final currentState = state;

    if (currentState is BudgetLoaded) {
      emit(BudgetUpdating(budget: currentState.budget));

      final result = await recordSpending(
        RecordSpendingParams(
          budgetId: event.budgetId,
          categoryId: event.categoryId,
          amount: event.amount,
          note: event.note,
        ),
      );

      result.fold(
        (failure) => emit(
          BudgetError(message: failure.message, budget: currentState.budget),
        ),
        (budget) => emit(BudgetLoaded(budget: budget)),
      );
    }
  }
}

</file>
<file path="lib/features/budget_tracking/presentation/bloc/budget_event.dart">
part of 'budget_bloc.dart';

/// Events for Budget BLoC
abstract class BudgetEvent extends Equatable {
  const BudgetEvent();

  @override
  List<Object> get props => [];
}

/// Load the current budget
class LoadBudget extends BudgetEvent {}

/// Create a new budget
class CreateNewBudget extends BudgetEvent {
  final double amount;
  final String period;

  const CreateNewBudget({required this.amount, required this.period});

  @override
  List<Object> get props => [amount, period];
}

/// Update budget categories
class UpdateCategories extends BudgetEvent {
  final String budgetId;
  final List<BudgetCategory> categories;

  const UpdateCategories({required this.budgetId, required this.categories});

  @override
  List<Object> get props => [budgetId, categories];
}

/// Add a new expense to a budget category
class AddExpense extends BudgetEvent {
  final String budgetId;
  final String categoryId;
  final double amount;
  final String? note;

  const AddExpense({
    required this.budgetId,
    required this.categoryId,
    required this.amount,
    this.note,
  });

  @override
  List<Object> get props => [budgetId, categoryId, amount, note ?? ''];
}

</file>
<file path="lib/features/budget_tracking/presentation/bloc/budget_state.dart">
part of 'budget_bloc.dart';

/// States for Budget BLoC
abstract class BudgetState extends Equatable {
  const BudgetState();

  @override
  List<Object?> get props => [];
}

/// Initial state before any budget is loaded
class BudgetInitial extends BudgetState {}

/// Loading state while fetching budget data
class BudgetLoading extends BudgetState {}

/// State for updating an existing budget
class BudgetUpdating extends BudgetState {
  final Budget budget;

  const BudgetUpdating({required this.budget});

  @override
  List<Object?> get props => [budget];
}

/// State when budget data is loaded successfully
class BudgetLoaded extends BudgetState {
  final Budget budget;

  const BudgetLoaded({required this.budget});

  @override
  List<Object?> get props => [budget];
}

/// Error state
class BudgetError extends BudgetState {
  final String message;
  final Budget? budget;

  const BudgetError({required this.message, this.budget});

  @override
  List<Object?> get props => [message, budget];
}

</file>
<file path="lib/features/budget_tracking/presentation/pages/add_expense_page.dart">
import 'package:flutter/material.dart';
import 'package:flutter/services.dart';
import 'package:flutter_bloc/flutter_bloc.dart';

import '../../../../core/theme/app_theme.dart';
import '../../domain/entities/budget.dart';
import '../bloc/budget_bloc.dart';

/// Page to add expenses to budget categories
class AddExpensePage extends StatefulWidget {
  final Budget budget;
  final BudgetCategory? initialCategory;

  const AddExpensePage({super.key, required this.budget, this.initialCategory});

  @override
  State<AddExpensePage> createState() => _AddExpensePageState();
}

class _AddExpensePageState extends State<AddExpensePage> {
  final _formKey = GlobalKey<FormState>();
  late BudgetCategory _selectedCategory;
  final TextEditingController _amountController = TextEditingController();
  final TextEditingController _noteController = TextEditingController();

  @override
  void initState() {
    super.initState();
    // Set initial category or first category
    _selectedCategory =
        widget.initialCategory ?? widget.budget.categories.first;
  }

  @override
  void dispose() {
    _amountController.dispose();
    _noteController.dispose();
    super.dispose();
  }

  void _saveExpense() {
    if (_formKey.currentState!.validate()) {
      final double amount = double.parse(_amountController.text);
      final String note = _noteController.text;

      // Add the expense through the BudgetBloc
      context.read<BudgetBloc>().add(
        AddExpense(
          budgetId: widget.budget.id,
          categoryId: _selectedCategory.id,
          amount: amount,
          note: note.isNotEmpty ? note : null,
        ),
      );

      Navigator.of(context).pop();
    }
  }

  @override
  Widget build(BuildContext context) {
    return Scaffold(
      backgroundColor: AppTheme.darkNavy,
      appBar: AppBar(
        backgroundColor: AppTheme.darkNavy,
        title: const Text('Add Expense'),
      ),
      body: Form(
        key: _formKey,
        child: SingleChildScrollView(
          padding: const EdgeInsets.all(16),
          child: Column(
            crossAxisAlignment: CrossAxisAlignment.start,
            children: [
              // Category selector
              Text(
                'Category',
                style: TextStyle(
                  color: AppTheme.white,
                  fontSize: 16,
                  fontWeight: FontWeight.w600,
                ),
              ),
              const SizedBox(height: 8),
              _buildCategoryDropdown(),
              const SizedBox(height: 24),

              // Amount field
              Text(
                'Amount',
                style: TextStyle(
                  color: AppTheme.white,
                  fontSize: 16,
                  fontWeight: FontWeight.w600,
                ),
              ),
              const SizedBox(height: 8),
              TextFormField(
                controller: _amountController,
                keyboardType: TextInputType.numberWithOptions(decimal: true),
                style: TextStyle(color: AppTheme.white),
                decoration: InputDecoration(
                  hintText: '0.00',
                  prefixText: '\$ ',
                  prefixStyle: TextStyle(color: AppTheme.primaryOrange),
                  filled: true,
                  fillColor: AppTheme.charcoal,
                  border: OutlineInputBorder(
                    borderRadius: BorderRadius.circular(8),
                    borderSide: BorderSide.none,
                  ),
                ),
                validator: (value) {
                  if (value == null || value.isEmpty) {
                    return 'Please enter an amount';
                  }
                  try {
                    final amount = double.parse(value);
                    if (amount <= 0) {
                      return 'Amount must be greater than 0';
                    }
                  } catch (e) {
                    return 'Please enter a valid number';
                  }
                  return null;
                },
                inputFormatters: [
                  FilteringTextInputFormatter.allow(RegExp(r'^\d+\.?\d{0,2}')),
                ],
              ),
              const SizedBox(height: 24),

              // Note field
              Text(
                'Note (Optional)',
                style: TextStyle(
                  color: AppTheme.white,
                  fontSize: 16,
                  fontWeight: FontWeight.w600,
                ),
              ),
              const SizedBox(height: 8),
              TextFormField(
                controller: _noteController,
                style: TextStyle(color: AppTheme.white),
                decoration: InputDecoration(
                  hintText: 'e.g. Groceries at Trader Joe\'s',
                  filled: true,
                  fillColor: AppTheme.charcoal,
                  border: OutlineInputBorder(
                    borderRadius: BorderRadius.circular(8),
                    borderSide: BorderSide.none,
                  ),
                ),
              ),
              const SizedBox(height: 32),

              // Submit button
              SizedBox(
                width: double.infinity,
                child: ElevatedButton(
                  onPressed: _saveExpense,
                  style: ElevatedButton.styleFrom(
                    padding: const EdgeInsets.symmetric(vertical: 16),
                    backgroundColor: AppTheme.primaryOrange,
                  ),
                  child: Text(
                    'Save Expense',
                    style: TextStyle(fontSize: 16, fontWeight: FontWeight.w600),
                  ),
                ),
              ),
            ],
          ),
        ),
      ),
    );
  }

  Widget _buildCategoryDropdown() {
    return Container(
      padding: const EdgeInsets.symmetric(horizontal: 16),
      decoration: BoxDecoration(
        color: AppTheme.charcoal,
        borderRadius: BorderRadius.circular(8),
      ),
      child: DropdownButtonHideUnderline(
        child: DropdownButton<BudgetCategory>(
          value: _selectedCategory,
          isExpanded: true,
          dropdownColor: AppTheme.slate,
          style: TextStyle(color: AppTheme.white),
          items: widget.budget.categories.map((category) {
            return DropdownMenuItem<BudgetCategory>(
              value: category,
              child: Text(
                category.name,
                style: TextStyle(color: AppTheme.white),
              ),
            );
          }).toList(),
          onChanged: (newValue) {
            if (newValue != null) {
              setState(() {
                _selectedCategory = newValue;
              });
            }
          },
        ),
      ),
    );
  }
}

</file>
<file path="lib/features/budget_tracking/presentation/pages/budget_page.dart">
import 'package:flutter/material.dart';
import 'package:flutter_bloc/flutter_bloc.dart';
import '../../../../core/theme/app_theme.dart';
import '../../../../core/routes/app_router.dart';
import '../../../../core/widgets/loading_indicator.dart';
import '../bloc/budget_bloc.dart';
import '../../domain/entities/budget.dart';
import '../widgets/budget_category_tile.dart';
import '../widgets/budget_overview_card.dart';

/// Budget tracking and optimization page
class BudgetPage extends StatefulWidget {
  const BudgetPage({super.key});

  @override
  State<BudgetPage> createState() => _BudgetPageState();
}

class _BudgetPageState extends State<BudgetPage> {
  @override
  void initState() {
    super.initState();
    // Load the budget when the page is first opened
    context.read<BudgetBloc>().add(LoadBudget());
  }

  /// Navigate to create budget page
  void _navigateToCreateBudget() {
    Navigator.pushNamed(context, AppRouter.createBudget);
  }

  /// Navigate to add expense page
  void _showAddExpenseDialog(
    BuildContext context, {
    BudgetCategory? initialCategory,
  }) {
    final state = context.read<BudgetBloc>().state;
    if (state is! BudgetLoaded) return;

    Navigator.pushNamed(
      context,
      AppRouter.addExpense,
      arguments: {'budget': state.budget, 'initialCategory': initialCategory},
    );
  }

  /// Show empty state when no budget exists
  Widget _buildEmptyState() {
    return Center(
      child: Column(
        mainAxisAlignment: MainAxisAlignment.center,
        children: [
          const Icon(
            Icons.account_balance_wallet_outlined,
            size: 64,
            color: Colors.white54,
          ),
          const SizedBox(height: 16),
          Text(
            'No Budget Set',
            style: Theme.of(context).textTheme.headlineSmall?.copyWith(
              color: Colors.white,
              fontWeight: FontWeight.bold,
            ),
          ),
          const SizedBox(height: 8),
          Text(
            'Create a budget to start tracking your grocery spending',
            textAlign: TextAlign.center,
            style: Theme.of(
              context,
            ).textTheme.bodyMedium?.copyWith(color: Colors.white70),
          ),
          const SizedBox(height: 24),
          ElevatedButton(
            onPressed: _navigateToCreateBudget,
            style: ElevatedButton.styleFrom(
              padding: const EdgeInsets.symmetric(horizontal: 32, vertical: 16),
              shape: RoundedRectangleBorder(
                borderRadius: BorderRadius.circular(12),
              ),
            ),
            child: const Text('Create Budget'),
          ),
        ],
      ),
    );
  }

  /// Build the main budget content
  Widget _buildBudgetContent(Budget budget) {
    return ListView(
      padding: const EdgeInsets.symmetric(horizontal: 16, vertical: 24),
      children: [
        BudgetOverviewCard(budget: budget),
        const SizedBox(height: 24),
        Text(
          'Categories',
          style: Theme.of(context).textTheme.titleLarge?.copyWith(
            color: Colors.white,
            fontWeight: FontWeight.bold,
          ),
        ),
        const SizedBox(height: 16),
        ...budget.categories
            .map(
              (category) => Padding(
                padding: const EdgeInsets.only(bottom: 8),
                child: BudgetCategoryTile(
                  category: category,
                  onTap: () =>
                      _showAddExpenseDialog(context, initialCategory: category),
                ),
              ),
            )
            .toList(),
      ],
    );
  }

  @override
  Widget build(BuildContext context) {
    return BlocBuilder<BudgetBloc, BudgetState>(
      builder: (context, state) {
        return Scaffold(
          backgroundColor: AppTheme.darkNavy,
          appBar: AppBar(
            backgroundColor: AppTheme.darkNavy,
            title: const Text('Budget'),
            actions: state is BudgetLoaded
                ? [
                    IconButton(
                      icon: const Icon(Icons.add_circle_outline),
                      onPressed: () => _showAddExpenseDialog(context),
                      tooltip: 'Add Expense',
                    ),
                  ]
                : null,
          ),
          floatingActionButton: state is BudgetLoaded
              ? FloatingActionButton(
                  onPressed: _navigateToCreateBudget,
                  backgroundColor: AppTheme.primaryOrange,
                  child: const Icon(Icons.post_add_rounded),
                  tooltip: 'Create New Budget',
                )
              : null,
          body: state is BudgetInitial
              ? _buildEmptyState()
              : state is BudgetLoading
              ? const Center(child: LoadingIndicator())
              : state is BudgetError
              ? Center(
                  child: Text(
                    state.message,
                    style: const TextStyle(color: Colors.white),
                  ),
                )
              : state is BudgetLoaded
              ? _buildBudgetContent(state.budget)
              : const SizedBox.shrink(),
        );
      },
    );
  }
}

</file>
<file path="lib/features/budget_tracking/presentation/pages/create_budget_page.dart">
import 'package:flutter/material.dart';
import 'package:flutter/services.dart';
import 'package:flutter_bloc/flutter_bloc.dart';

import '../../../../core/theme/app_theme.dart';
import '../../../../core/widgets/loading_indicator.dart';
import '../bloc/budget_bloc.dart';

/// Page to create a new budget
class CreateBudgetPage extends StatefulWidget {
  const CreateBudgetPage({super.key});

  @override
  State<CreateBudgetPage> createState() => _CreateBudgetPageState();
}

class _CreateBudgetPageState extends State<CreateBudgetPage> {
  final _formKey = GlobalKey<FormState>();
  final TextEditingController _amountController = TextEditingController();
  String _selectedPeriod = 'monthly';
  bool _isSubmitting = false;

  @override
  void dispose() {
    _amountController.dispose();
    super.dispose();
  }

  String? _validateAmount(String? value) {
    if (value == null || value.isEmpty) {
      return 'Please enter a budget amount';
    }
    final amount = double.tryParse(value);
    if (amount == null) {
      return 'Please enter a valid number';
    }
    if (amount <= 0) {
      return 'Amount must be greater than zero';
    }
    return null;
  }

  void _saveBudget() {
    if (!_formKey.currentState!.validate()) return;

    setState(() => _isSubmitting = true);

    final double amount = double.parse(_amountController.text);

    context.read<BudgetBloc>().add(
      CreateNewBudget(amount: amount, period: _selectedPeriod),
    );
  }

  @override
  Widget build(BuildContext context) {
    return BlocListener<BudgetBloc, BudgetState>(
      listener: (context, state) {
        if (state is BudgetError) {
          setState(() => _isSubmitting = false);
          ScaffoldMessenger.of(context).showSnackBar(
            SnackBar(content: Text(state.message), backgroundColor: Colors.red),
          );
        } else if (state is BudgetLoaded) {
          Navigator.of(context).pop();
        }
      },
      child: Scaffold(
        backgroundColor: AppTheme.darkNavy,
        appBar: AppBar(
          backgroundColor: AppTheme.darkNavy,
          title: const Text('Create New Budget'),
          elevation: 0,
          actions: [
            IconButton(
              icon: const Icon(Icons.help_outline),
              onPressed: () {
                showDialog(
                  context: context,
                  builder: (context) => AlertDialog(
                    backgroundColor: AppTheme.darkNavy,
                    title: const Text(
                      'About Budget Categories',
                      style: TextStyle(color: Colors.white),
                    ),
                    content: const Text(
                      'Your budget will be automatically divided into recommended categories:\n\n'
                      '• Produce (30%)\n'
                      '• Protein (25%)\n'
                      '• Dairy (15%)\n'
                      '• Grains & Pasta (10%)\n'
                      '• Snacks (10%)\n'
                      '• Beverages (5%)\n'
                      '• Other (5%)\n\n'
                      'You can adjust these later in settings.',
                      style: TextStyle(color: Colors.white70),
                    ),
                    actions: [
                      TextButton(
                        child: const Text('Got it'),
                        onPressed: () => Navigator.of(context).pop(),
                      ),
                    ],
                  ),
                );
              },
            ),
          ],
        ),
        body: _isSubmitting
            ? const Center(child: LoadingIndicator())
            : Column(
                children: [
                  Expanded(
                    child: SingleChildScrollView(
                      padding: const EdgeInsets.all(16),
                      child: Form(
                        key: _formKey,
                        child: Column(
                          crossAxisAlignment: CrossAxisAlignment.stretch,
                          children: [
                            const Icon(
                              Icons.account_balance_wallet_rounded,
                              size: 64,
                              color: AppTheme.primaryOrange,
                            ),
                            const SizedBox(height: 24),
                            Text(
                              'Set Your Budget',
                              style: Theme.of(context).textTheme.headlineMedium
                                  ?.copyWith(
                                    color: Colors.white,
                                    fontWeight: FontWeight.bold,
                                  ),
                              textAlign: TextAlign.center,
                            ),
                            const SizedBox(height: 8),
                            Text(
                              'Enter your budget amount and choose a period. We\'ll help you track your spending and stay on target.',
                              style: Theme.of(context).textTheme.bodyMedium
                                  ?.copyWith(color: Colors.white70),
                              textAlign: TextAlign.center,
                            ),
                            const SizedBox(height: 32),
                            TextFormField(
                              controller: _amountController,
                              validator: _validateAmount,
                              keyboardType:
                                  const TextInputType.numberWithOptions(
                                    decimal: true,
                                  ),
                              inputFormatters: [
                                FilteringTextInputFormatter.allow(
                                  RegExp(r'^\d+\.?\d{0,2}'),
                                ),
                              ],
                              style: const TextStyle(
                                color: Colors.white,
                                fontSize: 24,
                                fontWeight: FontWeight.bold,
                              ),
                              textAlign: TextAlign.center,
                              decoration: InputDecoration(
                                labelText: 'Budget Amount',
                                prefixIcon: const Padding(
                                  padding: EdgeInsets.symmetric(horizontal: 16),
                                  child: Icon(
                                    Icons.attach_money,
                                    color: Colors.white70,
                                    size: 28,
                                  ),
                                ),
                                labelStyle: const TextStyle(
                                  color: Colors.white70,
                                ),
                                border: OutlineInputBorder(
                                  borderRadius: BorderRadius.circular(16),
                                ),
                                enabledBorder: OutlineInputBorder(
                                  borderRadius: BorderRadius.circular(16),
                                  borderSide: const BorderSide(
                                    color: Colors.white24,
                                  ),
                                ),
                                focusedBorder: OutlineInputBorder(
                                  borderRadius: BorderRadius.circular(16),
                                  borderSide: BorderSide(
                                    color: Theme.of(context).primaryColor,
                                    width: 2,
                                  ),
                                ),
                                contentPadding: const EdgeInsets.symmetric(
                                  horizontal: 24,
                                  vertical: 20,
                                ),
                              ),
                            ),
                            const SizedBox(height: 24),
                            Container(
                              padding: const EdgeInsets.symmetric(
                                horizontal: 12,
                              ),
                              decoration: BoxDecoration(
                                border: Border.all(color: Colors.white24),
                                borderRadius: BorderRadius.circular(16),
                              ),
                              child: DropdownButtonHideUnderline(
                                child: DropdownButton<String>(
                                  value: _selectedPeriod,
                                  dropdownColor: AppTheme.darkNavy,
                                  style: const TextStyle(
                                    color: Colors.white,
                                    fontSize: 16,
                                  ),
                                  icon: const Icon(
                                    Icons.arrow_drop_down,
                                    color: Colors.white70,
                                  ),
                                  isExpanded: true,
                                  items: [
                                    DropdownMenuItem(
                                      value: 'weekly',
                                      child: Text(
                                        'Weekly Budget',
                                        style: TextStyle(
                                          color: _selectedPeriod == 'weekly'
                                              ? Theme.of(context).primaryColor
                                              : Colors.white,
                                        ),
                                      ),
                                    ),
                                    DropdownMenuItem(
                                      value: 'monthly',
                                      child: Text(
                                        'Monthly Budget',
                                        style: TextStyle(
                                          color: _selectedPeriod == 'monthly'
                                              ? Theme.of(context).primaryColor
                                              : Colors.white,
                                        ),
                                      ),
                                    ),
                                  ],
                                  onChanged: (String? value) {
                                    if (value != null) {
                                      setState(() => _selectedPeriod = value);
                                    }
                                  },
                                ),
                              ),
                            ),
                          ],
                        ),
                      ),
                    ),
                  ),
                  SafeArea(
                    child: Padding(
                      padding: const EdgeInsets.all(16),
                      child: SizedBox(
                        width: double.infinity,
                        height: 56,
                        child: ElevatedButton(
                          onPressed: _saveBudget,
                          style: ElevatedButton.styleFrom(
                            backgroundColor: Theme.of(context).primaryColor,
                            shape: RoundedRectangleBorder(
                              borderRadius: BorderRadius.circular(16),
                            ),
                          ),
                          child: const Text(
                            'Create Budget',
                            style: TextStyle(
                              fontSize: 18,
                              fontWeight: FontWeight.bold,
                            ),
                          ),
                        ),
                      ),
                    ),
                  ),
                ],
              ),
      ),
    );
  }
}

</file>
<file path="lib/features/budget_tracking/presentation/widgets/budget_category_tile.dart">
import 'package:flutter/material.dart';

import '../../../../core/theme/app_theme.dart';
import '../../domain/entities/budget.dart';

/// A tile that displays budget category information
class BudgetCategoryTile extends StatelessWidget {
  /// The budget category to display
  final BudgetCategory category;

  /// Called when the tile is tapped
  final VoidCallback? onTap;

  /// Creates a budget category tile
  const BudgetCategoryTile({super.key, required this.category, this.onTap});

  Color _getCategoryColor() {
    switch (category.name.toLowerCase()) {
      case 'produce':
        return AppTheme.primaryGreen;
      case 'protein':
        return AppTheme.primaryCoral;
      case 'dairy':
        return AppTheme.accentPurple;
      case 'grains & pasta':
        return AppTheme.accentYellow;
      case 'snacks':
        return AppTheme.primaryOrange;
      case 'beverages':
        return AppTheme.accentBlue;
      default:
        return AppTheme.mediumGray;
    }
  }

  @override
  Widget build(BuildContext context) {
    final progress = category.spent / category.allocation;
    final remaining = category.allocation - category.spent;
    final isOverBudget = remaining < 0;

    return Card(
      color: AppTheme.darkNavy.withOpacity(0.5),
      shape: RoundedRectangleBorder(
        borderRadius: BorderRadius.circular(12),
        side: BorderSide(color: _getCategoryColor().withOpacity(0.3), width: 1),
      ),
      child: InkWell(
        onTap: onTap,
        borderRadius: BorderRadius.circular(12),
        child: Padding(
          padding: const EdgeInsets.all(16),
          child: Column(
            crossAxisAlignment: CrossAxisAlignment.start,
            children: [
              Row(
                mainAxisAlignment: MainAxisAlignment.spaceBetween,
                children: [
                  Text(
                    category.name,
                    style: Theme.of(context).textTheme.titleMedium?.copyWith(
                      color: Colors.white,
                      fontWeight: FontWeight.w600,
                    ),
                  ),
                  Text(
                    '\${category.spent.toStringAsFixed(2)} / \${category.allocation.toStringAsFixed(2)}',
                    style: Theme.of(
                      context,
                    ).textTheme.bodyMedium?.copyWith(color: Colors.white70),
                  ),
                ],
              ),
              const SizedBox(height: 8),
              ClipRRect(
                borderRadius: BorderRadius.circular(4),
                child: LinearProgressIndicator(
                  value: progress.clamp(0.0, 1.0),
                  backgroundColor: Colors.white12,
                  valueColor: AlwaysStoppedAnimation<Color>(
                    isOverBudget ? Colors.red : _getCategoryColor(),
                  ),
                ),
              ),
              const SizedBox(height: 8),
              Text(
                isOverBudget
                    ? 'Over budget by \${(-remaining).toStringAsFixed(2)}'
                    : '\${remaining.toStringAsFixed(2)} remaining',
                style: Theme.of(context).textTheme.bodySmall?.copyWith(
                  color: isOverBudget ? Colors.red : Colors.white60,
                ),
              ),
            ],
          ),
        ),
      ),
    );
  }
}

</file>
<file path="lib/features/budget_tracking/presentation/widgets/budget_overview_card.dart">
import 'package:flutter/material.dart';

import '../../../../core/theme/app_theme.dart';
import '../../domain/entities/budget.dart';

/// Widget that shows budget overview information
class BudgetOverviewCard extends StatelessWidget {
  final Budget budget;

  const BudgetOverviewCard({super.key, required this.budget});

  @override
  Widget build(BuildContext context) {
    final isOverBudget = budget.isOverBudget;
    final percentageSpent = budget.percentageSpent;

    return Container(
      padding: const EdgeInsets.all(16),
      decoration: BoxDecoration(
        gradient: AppTheme.darkGradient,
        borderRadius: BorderRadius.circular(16),
      ),
      child: Column(
        crossAxisAlignment: CrossAxisAlignment.start,
        children: [
          Row(
            mainAxisAlignment: MainAxisAlignment.spaceBetween,
            children: [
              Text(
                '${budget.period.substring(0, 1).toUpperCase()}${budget.period.substring(1)} Budget',
                style: TextStyle(
                  color: AppTheme.white,
                  fontSize: 16,
                  fontWeight: FontWeight.w600,
                ),
              ),
              Text(
                '${budget.daysLeft} days left',
                style: TextStyle(color: AppTheme.mediumGray, fontSize: 14),
              ),
            ],
          ),
          const SizedBox(height: 24),
          Row(
            mainAxisAlignment: MainAxisAlignment.spaceBetween,
            children: [
              // Budget amount
              _buildAmountDisplay(
                label: 'Total Budget',
                amount: budget.amount,
                color: AppTheme.white,
              ),
              // Spent amount
              _buildAmountDisplay(
                label: 'Spent',
                amount: budget.spent,
                color: isOverBudget
                    ? AppTheme.accentPink
                    : AppTheme.primaryOrange,
              ),
              // Remaining amount
              _buildAmountDisplay(
                label: 'Remaining',
                amount: budget.remaining,
                color: isOverBudget
                    ? AppTheme.accentPink
                    : AppTheme.primaryPeach,
              ),
            ],
          ),
          const SizedBox(height: 24),
          // Progress bar
          Stack(
            children: [
              // Background track
              Container(
                height: 12,
                width: double.infinity,
                decoration: BoxDecoration(
                  color: AppTheme.slate.withOpacity(0.3),
                  borderRadius: BorderRadius.circular(6),
                ),
              ),
              // Progress indicator
              Container(
                height: 12,
                width:
                    MediaQuery.of(context).size.width *
                    (percentageSpent / 100) *
                    0.85, // Adjust for padding
                decoration: BoxDecoration(
                  color: isOverBudget
                      ? AppTheme.accentPink
                      : percentageSpent > 80
                      ? AppTheme.accentYellow
                      : AppTheme.primaryOrange,
                  borderRadius: BorderRadius.circular(6),
                ),
              ),
            ],
          ),
          const SizedBox(height: 12),
          // Percentage used
          Row(
            mainAxisAlignment: MainAxisAlignment.spaceBetween,
            children: [
              Text(
                '${percentageSpent.toStringAsFixed(1)}% used',
                style: TextStyle(color: AppTheme.mediumGray, fontSize: 14),
              ),
              if (budget.dailyBudget > 0)
                Text(
                  '\${budget.dailyBudget.toStringAsFixed(2)} per day left',
                  style: TextStyle(color: AppTheme.mediumGray, fontSize: 14),
                ),
            ],
          ),
        ],
      ),
    );
  }

  Widget _buildAmountDisplay({
    required String label,
    required double amount,
    required Color color,
  }) {
    return Column(
      crossAxisAlignment: CrossAxisAlignment.start,
      children: [
        Text(label, style: TextStyle(color: AppTheme.mediumGray, fontSize: 12)),
        const SizedBox(height: 4),
        Text(
          '\${amount.toStringAsFixed(2)}',
          style: TextStyle(
            color: color,
            fontSize: 22,
            fontWeight: FontWeight.bold,
          ),
        ),
      ],
    );
  }
}

</file>
<file path="lib/features/dashboard/presentation/pages/dashboard_page.dart">
import 'package:flutter/material.dart';
import '../../../../core/theme/app_theme.dart';
import '../../../meal_planning/presentation/pages/meal_plan_page.dart';
import '../../../grocery_list/presentation/pages/grocery_list_page.dart';
import '../../../budget_tracking/presentation/pages/budget_page.dart';
import '../../../profile/presentation/pages/profile_page.dart';
import '../widgets/dashboard_home.dart';

/// Main dashboard with bottom navigation
class DashboardPage extends StatefulWidget {
  const DashboardPage({super.key});

  /// Tab indices for quick navigation
  static const int homeTab = 0;
  static const int mealPlanTab = 1;
  static const int groceryListTab = 2;
  static const int budgetTab = 3;
  static const int profileTab = 4;

  /// Navigate to a specific tab in the dashboard
  static void navigateToTab(BuildContext context, int tabIndex) {
    final dashboardState = context
        .findAncestorStateOfType<_DashboardPageState>();
    if (dashboardState != null) {
      dashboardState._onItemTapped(tabIndex);
    } else {
      // If we're not already in dashboard, navigate to it first
      Navigator.pushNamedAndRemoveUntil(
        context,
        '/dashboard',
        (route) => false,
      ).then((_) {
        // After navigation, try to set the tab again
        final newState = context.findAncestorStateOfType<_DashboardPageState>();
        newState?._onItemTapped(tabIndex);
      });
    }
  }

  @override
  State<DashboardPage> createState() => _DashboardPageState();
}

class _DashboardPageState extends State<DashboardPage> {
  int _selectedIndex = 0;

  // Pages to show in the bottom navigation
  final List<Widget> _pages = [
    const DashboardHome(),
    const MealPlanPage(),
    const GroceryListPage(),
    const BudgetPage(),
    const ProfilePage(),
  ];

  void _onItemTapped(int index) {
    setState(() {
      _selectedIndex = index;
    });
  }

  @override
  Widget build(BuildContext context) {
    return Scaffold(
      body: _pages[_selectedIndex],
      bottomNavigationBar: Container(
        decoration: BoxDecoration(
          color: AppTheme.darkNavy,
          boxShadow: [
            BoxShadow(
              color: Colors.black.withOpacity(0.3),
              blurRadius: 10,
              offset: const Offset(0, -2),
            ),
          ],
        ),
        child: SafeArea(
          child: Padding(
            padding: const EdgeInsets.symmetric(vertical: 8),
            child: BottomNavigationBar(
              currentIndex: _selectedIndex,
              onTap: _onItemTapped,
              backgroundColor: Colors.transparent,
              type: BottomNavigationBarType.fixed,
              selectedItemColor: AppTheme.primaryOrange,
              unselectedItemColor: AppTheme.mediumGray,
              showSelectedLabels: true,
              showUnselectedLabels: true,
              elevation: 0,
              items: const [
                BottomNavigationBarItem(
                  icon: Icon(Icons.home_outlined),
                  activeIcon: Icon(Icons.home_rounded),
                  label: 'Home',
                ),
                BottomNavigationBarItem(
                  icon: Icon(Icons.calendar_month_outlined),
                  activeIcon: Icon(Icons.calendar_month_rounded),
                  label: 'Meal Plan',
                ),
                BottomNavigationBarItem(
                  icon: Icon(Icons.shopping_cart_outlined),
                  activeIcon: Icon(Icons.shopping_cart_rounded),
                  label: 'Groceries',
                ),
                BottomNavigationBarItem(
                  icon: Icon(Icons.account_balance_wallet_outlined),
                  activeIcon: Icon(Icons.account_balance_wallet_rounded),
                  label: 'Budget',
                ),
                BottomNavigationBarItem(
                  icon: Icon(Icons.person_outline),
                  activeIcon: Icon(Icons.person_rounded),
                  label: 'Profile',
                ),
              ],
            ),
          ),
        ),
      ),
    );
  }
}

</file>
<file path="lib/features/dashboard/presentation/widgets/dashboard_home.dart">
import 'package:flutter/material.dart';
import 'package:cached_network_image/cached_network_image.dart';
import '../../../../core/theme/app_theme.dart';
import '../../../../core/routes/app_router.dart';
import '../pages/dashboard_page.dart';
import '../../../recipes/data/models/recipe_model.dart';

/// Home view of the dashboard
class DashboardHome extends StatelessWidget {
  const DashboardHome({super.key});

  @override
  Widget build(BuildContext context) {
    return Scaffold(
      backgroundColor: AppTheme.darkNavy,
      body: SafeArea(
        child: SingleChildScrollView(
          child: Padding(
            padding: const EdgeInsets.all(16.0),
            child: Column(
              crossAxisAlignment: CrossAxisAlignment.start,
              children: [
                // Header with greeting and profile
                _buildHeader(),
                const SizedBox(height: 24),

                // Quick stats cards
                _buildQuickStats(),
                const SizedBox(height: 32),

                // Today's meals section
                _buildTodaysMeals(context),
                const SizedBox(height: 32),

                // Quick actions section here if needed
                _buildQuickActions(context),
              ],
            ),
          ),
        ),
      ),
    );
  }

  Widget _buildHeader() {
    return Row(
      mainAxisAlignment: MainAxisAlignment.spaceBetween,
      children: [
        Column(
          crossAxisAlignment: CrossAxisAlignment.start,
          children: [
            const Text(
              'Good Morning,',
              style: TextStyle(
                fontSize: 16,
                fontWeight: FontWeight.w400,
                color: AppTheme.mediumGray,
              ),
            ),
            const SizedBox(height: 4),
            Text('Alex', style: AppTheme.h1),
          ],
        ),
        Container(
          height: 50,
          width: 50,
          decoration: BoxDecoration(
            color: AppTheme.slate,
            borderRadius: BorderRadius.circular(25),
            border: Border.all(color: AppTheme.primaryOrange, width: 2),
          ),
          child: const Center(
            child: Icon(Icons.person, color: AppTheme.white, size: 28),
          ),
        ),
      ],
    );
  }

  Widget _buildQuickStats() {
    return Row(
      children: [
        // Budget progress
        Expanded(
          child: _buildStatCard(
            icon: Icons.account_balance_wallet_rounded,
            iconColor: AppTheme.primaryCoral,
            title: 'Budget',
            value: '\$156',
            subtitle: 'of \$200 used',
            progress: 0.78,
          ),
        ),
        const SizedBox(width: 16),

        // Meal progress
        Expanded(
          child: _buildStatCard(
            icon: Icons.restaurant_rounded,
            iconColor: AppTheme.accentYellow,
            title: 'Meals',
            value: '12',
            subtitle: 'of 21 planned',
            progress: 0.57,
          ),
        ),
      ],
    );
  }

  Widget _buildStatCard({
    required IconData icon,
    required Color iconColor,
    required String title,
    required String value,
    required String subtitle,
    required double progress,
  }) {
    return Container(
      padding: const EdgeInsets.all(16),
      decoration: BoxDecoration(
        color: AppTheme.charcoal,
        borderRadius: BorderRadius.circular(20),
        boxShadow: [
          BoxShadow(
            color: Colors.black.withOpacity(0.1),
            blurRadius: 8,
            offset: const Offset(0, 2),
          ),
        ],
      ),
      child: Column(
        crossAxisAlignment: CrossAxisAlignment.start,
        children: [
          Row(
            children: [
              Container(
                padding: const EdgeInsets.all(8),
                decoration: BoxDecoration(
                  color: iconColor.withOpacity(0.2),
                  borderRadius: BorderRadius.circular(12),
                ),
                child: Icon(icon, color: iconColor, size: 20),
              ),
              const SizedBox(width: 8),
              Text(
                title,
                style: AppTheme.caption.copyWith(color: AppTheme.mediumGray),
              ),
            ],
          ),
          const SizedBox(height: 12),
          Row(
            children: [
              Text(value, style: AppTheme.h3),
              const SizedBox(width: 6),
              Text(
                subtitle,
                style: AppTheme.caption.copyWith(
                  color: AppTheme.mediumGray,
                  fontSize: 12,
                ),
              ),
            ],
          ),
          const SizedBox(height: 12),
          ClipRRect(
            borderRadius: BorderRadius.circular(10),
            child: LinearProgressIndicator(
              value: progress,
              backgroundColor: AppTheme.slate,
              valueColor: AlwaysStoppedAnimation<Color>(iconColor),
              minHeight: 6,
            ),
          ),
        ],
      ),
    );
  }

  Widget _buildTodaysMeals(BuildContext context) {
    final recipes = RecipeModel.getMockRecipes();
    // Show 3 meals for better variety
    final todaysMeals = recipes.take(3).toList();
    final mealTimes = ['8:00 AM', '12:30 PM', '6:30 PM'];

    return Column(
      crossAxisAlignment: CrossAxisAlignment.start,
      children: [
        Row(
          mainAxisAlignment: MainAxisAlignment.spaceBetween,
          children: [
            Text("Today's Meals", style: AppTheme.h2),
            TextButton(
              onPressed: () {
                DashboardPage.navigateToTab(context, DashboardPage.mealPlanTab);
              },
              child: Row(
                children: [
                  Text(
                    'View All',
                    style: TextStyle(
                      color: AppTheme.primaryOrange,
                      fontSize: 14,
                      fontWeight: FontWeight.w600,
                    ),
                  ),
                  const SizedBox(width: 4),
                  Icon(
                    Icons.arrow_forward_rounded,
                    color: AppTheme.primaryOrange,
                    size: 16,
                  ),
                ],
              ),
            ),
          ],
        ),
        const SizedBox(height: 16),
        ListView.separated(
          shrinkWrap: true,
          physics: const NeverScrollableScrollPhysics(),
          itemCount: todaysMeals.length,
          separatorBuilder: (context, index) => const SizedBox(height: 16),
          itemBuilder: (context, index) {
            final recipe = todaysMeals[index];
            return InkWell(
              onTap: () => Navigator.pushNamed(
                context,
                AppRouter.recipeDetail,
                arguments: {'recipeId': recipe.id},
              ),
              child: Container(
                height: 120, // Fixed height for consistency
                decoration: BoxDecoration(
                  color: AppTheme.slate,
                  borderRadius: BorderRadius.circular(16),
                ),
                child: Row(
                  children: [
                    // Image section
                    ClipRRect(
                      borderRadius: const BorderRadius.only(
                        topLeft: Radius.circular(16),
                        bottomLeft: Radius.circular(16),
                      ),
                      child: CachedNetworkImage(
                        imageUrl: recipe.imageUrl ?? '',
                        width: 120,
                        height: 120,
                        fit: BoxFit.cover,
                        placeholder: (context, url) => Container(
                          color: AppTheme.darkNavy,
                          child: const Center(
                            child: CircularProgressIndicator(
                              color: AppTheme.primaryOrange,
                            ),
                          ),
                        ),
                        errorWidget: (context, url, error) => Container(
                          color: AppTheme.darkNavy,
                          child: const Icon(
                            Icons.restaurant,
                            color: AppTheme.primaryOrange,
                            size: 32,
                          ),
                        ),
                      ),
                    ),
                    // Content section
                    Expanded(
                      child: Padding(
                        padding: const EdgeInsets.all(12),
                        child: Column(
                          crossAxisAlignment: CrossAxisAlignment.start,
                          children: [
                            // Time and meal type
                            Row(
                              mainAxisAlignment: MainAxisAlignment.spaceBetween,
                              children: [
                                Text(
                                  mealTimes[index],
                                  style: TextStyle(
                                    color: AppTheme.primaryOrange,
                                    fontSize: 14,
                                    fontWeight: FontWeight.w600,
                                  ),
                                ),
                                Container(
                                  padding: const EdgeInsets.symmetric(
                                    horizontal: 8,
                                    vertical: 4,
                                  ),
                                  decoration: BoxDecoration(
                                    color: AppTheme.darkNavy.withOpacity(0.8),
                                    borderRadius: BorderRadius.circular(12),
                                  ),
                                  child: Text(
                                    recipe.mealType?.toUpperCase() ?? 'MEAL',
                                    style: TextStyle(
                                      color: AppTheme.primaryOrange,
                                      fontSize: 12,
                                      fontWeight: FontWeight.w600,
                                    ),
                                  ),
                                ),
                              ],
                            ),
                            const SizedBox(height: 8),
                            Text(
                              recipe.name,
                              style: AppTheme.h3,
                              maxLines: 1,
                              overflow: TextOverflow.ellipsis,
                            ),
                            const Spacer(),
                            // Nutrition badges - always show all four
                            SingleChildScrollView(
                              scrollDirection: Axis.horizontal,
                              child: Row(
                                children: [
                                  _buildNutrientBadge(
                                    '${recipe.nutrition.calories}',
                                    'cal',
                                    AppTheme.primaryOrange,
                                  ),
                                  const SizedBox(width: 8),
                                  _buildNutrientBadge(
                                    '${recipe.nutrition.protein.value}g',
                                    'prot',
                                    AppTheme.accentPurple,
                                  ),
                                  const SizedBox(width: 8),
                                  _buildNutrientBadge(
                                    '${recipe.nutrition.carbs.value}g',
                                    'carbs',
                                    AppTheme.primaryCoral,
                                  ),
                                  const SizedBox(width: 8),
                                  _buildNutrientBadge(
                                    '${recipe.nutrition.fat.value}g',
                                    'fat',
                                    AppTheme.primaryPeach,
                                  ),
                                ],
                              ),
                            ),
                          ],
                        ),
                      ),
                    ),
                  ],
                ),
              ),
            );
          },
        ),
      ],
    );
  }

  Widget _buildNutrientBadge(String value, String label, Color color) {
    return Container(
      padding: const EdgeInsets.symmetric(horizontal: 8, vertical: 4),
      decoration: BoxDecoration(
        color: color.withOpacity(0.1),
        borderRadius: BorderRadius.circular(8),
        border: Border.all(color: color.withOpacity(0.3), width: 1),
      ),
      child: Row(
        mainAxisSize: MainAxisSize.min,
        children: [
          Text(
            value,
            style: TextStyle(
              color: color,
              fontSize: 12,
              fontWeight: FontWeight.w600,
            ),
          ),
          const SizedBox(width: 2),
          Text(
            label,
            style: TextStyle(color: color.withOpacity(0.8), fontSize: 10),
          ),
        ],
      ),
    );
  }

  Widget _buildQuickActions(BuildContext context) {
    final actions = [
      (
        Icons.shopping_cart_rounded,
        'Shopping\nList',
        AppTheme.primaryOrange,
        AppRouter.groceryList,
      ),
      (
        Icons.fastfood_rounded,
        'Meal\nPlanner',
        AppTheme.primaryCoral,
        AppRouter.mealPlan,
      ),
      (
        Icons.list_alt_rounded,
        'Recipes',
        AppTheme.primaryPeach,
        AppRouter.mealPlan,
      ),
      (
        Icons.local_dining_rounded,
        'Diet\nGoals',
        AppTheme.accentPurple,
        AppRouter.profile,
      ),
    ];

    return Column(
      crossAxisAlignment: CrossAxisAlignment.start,
      children: [
        Text('Quick Actions', style: AppTheme.h2),
        const SizedBox(height: 16),
        Row(
          mainAxisAlignment: MainAxisAlignment.spaceBetween,
          children: actions.map((action) {
            return _buildActionButton(
              context,
              icon: action.$1,
              label: action.$2,
              color: action.$3,
              route: action.$4,
            );
          }).toList(),
        ),
      ],
    );
  }

  Widget _buildActionButton(
    BuildContext context, {
    required IconData icon,
    required String label,
    required Color color,
    required String route,
  }) {
    return InkWell(
      onTap: () {
        Navigator.pushNamed(context, route);
      },
      child: Container(
        width: 80,
        height: 80,
        decoration: BoxDecoration(
          color: color.withOpacity(0.1),
          borderRadius: BorderRadius.circular(20),
          border: Border.all(color: color.withOpacity(0.3), width: 1),
        ),
        child: Column(
          mainAxisAlignment: MainAxisAlignment.center,
          children: [
            Icon(icon, color: color, size: 24),
            const SizedBox(height: 8),
            Text(
              label,
              style: TextStyle(
                color: color,
                fontSize: 12,
                fontWeight: FontWeight.w600,
              ),
              textAlign: TextAlign.center,
            ),
          ],
        ),
      ),
    );
  }
}

</file>
<file path="lib/features/grocery_list/presentation/pages/grocery_list_page.dart">
import 'package:flutter/material.dart';
import '../../../../core/theme/app_theme.dart';

/// Grocery list management page
class GroceryListPage extends StatefulWidget {
  const GroceryListPage({super.key});

  @override
  State<GroceryListPage> createState() => _GroceryListPageState();
}

class _GroceryListPageState extends State<GroceryListPage> {
  // Mock data for grocery categories
  final Map<String, List<Map<String, dynamic>>> _groceryCategories = {
    'Produce': [
      {
        'name': 'Spinach',
        'quantity': '1 bunch',
        'price': 2.99,
        'isChecked': false,
        'bestStore': 'Kroger',
      },
      {
        'name': 'Carrots',
        'quantity': '500g',
        'price': 1.49,
        'isChecked': true,
        'bestStore': 'Walmart',
      },
      {
        'name': 'Apples',
        'quantity': '4 medium',
        'price': 3.99,
        'isChecked': false,
        'bestStore': 'Whole Foods',
      },
    ],
    'Dairy': [
      {
        'name': 'Greek Yogurt',
        'quantity': '32 oz',
        'price': 5.99,
        'isChecked': false,
        'bestStore': 'Kroger',
      },
      {
        'name': 'Milk',
        'quantity': '1 gallon',
        'price': 3.49,
        'isChecked': false,
        'bestStore': 'Walmart',
      },
    ],
    'Protein': [
      {
        'name': 'Chicken Breast',
        'quantity': '2 lbs',
        'price': 8.99,
        'isChecked': false,
        'bestStore': 'Kroger',
      },
      {
        'name': 'Salmon',
        'quantity': '1 lb',
        'price': 12.99,
        'isChecked': false,
        'bestStore': 'Whole Foods',
      },
      {
        'name': 'Eggs',
        'quantity': '12 large',
        'price': 4.29,
        'isChecked': true,
        'bestStore': 'Walmart',
      },
    ],
    'Grains': [
      {
        'name': 'Brown Rice',
        'quantity': '2 lbs',
        'price': 3.99,
        'isChecked': false,
        'bestStore': 'Walmart',
      },
      {
        'name': 'Whole Wheat Bread',
        'quantity': '1 loaf',
        'price': 4.49,
        'isChecked': true,
        'bestStore': 'Kroger',
      },
    ],
  };

  // Track which categories are expanded
  final Map<String, bool> _expandedCategories = {};

  @override
  void initState() {
    super.initState();
    // Start with all categories expanded
    for (final category in _groceryCategories.keys) {
      _expandedCategories[category] = true;
    }
  }

  void _toggleCategory(String category) {
    setState(() {
      _expandedCategories[category] = !(_expandedCategories[category] ?? false);
    });
  }

  void _toggleItemChecked(String category, int index) {
    setState(() {
      final item = _groceryCategories[category]![index];
      item['isChecked'] = !(item['isChecked'] as bool);
    });
  }

  @override
  Widget build(BuildContext context) {
    return Scaffold(
      backgroundColor: AppTheme.darkNavy,
      appBar: AppBar(
        title: const Text('Grocery List'),
        actions: [
          IconButton(
            icon: const Icon(Icons.search),
            onPressed: () {
              // TODO: Implement search functionality
            },
          ),
          IconButton(
            icon: const Icon(Icons.more_vert),
            onPressed: () {
              // TODO: Implement menu options
            },
          ),
        ],
      ),
      body: Column(
        children: [
          // Summary card
          _buildSummaryCard(),

          // Grocery list
          Expanded(
            child: _groceryCategories.isEmpty
                ? _buildEmptyState()
                : _buildGroceryList(),
          ),
        ],
      ),
      floatingActionButton: FloatingActionButton.extended(
        backgroundColor: AppTheme.primaryOrange,
        onPressed: () {
          // TODO: Implement share/export functionality
        },
        icon: const Icon(Icons.share),
        label: const Text('Share List'),
      ),
    );
  }

  Widget _buildSummaryCard() {
    // Calculate summary data
    int totalItems = 0;
    int checkedItems = 0;
    double totalPrice = 0.0;

    _groceryCategories.values.forEach((items) {
      totalItems += items.length;
      checkedItems += items.where((item) => item['isChecked'] as bool).length;
      items.forEach((item) {
        totalPrice += item['price'] as double;
      });
    });

    return Padding(
      padding: const EdgeInsets.all(16.0),
      child: Container(
        padding: const EdgeInsets.all(16),
        decoration: BoxDecoration(
          color: AppTheme.charcoal,
          borderRadius: BorderRadius.circular(20),
        ),
        child: Row(
          mainAxisAlignment: MainAxisAlignment.spaceAround,
          children: [
            _buildSummaryItem(
              icon: Icons.shopping_cart,
              value: '$totalItems',
              label: 'Items',
              color: AppTheme.primaryOrange,
            ),
            _buildSummaryItem(
              icon: Icons.check_circle,
              value: '$checkedItems',
              label: 'Checked',
              color: AppTheme.accentYellow,
            ),
            _buildSummaryItem(
              icon: Icons.attach_money,
              value: '\${totalPrice.toStringAsFixed(2)}',
              label: 'Total',
              color: AppTheme.primaryCoral,
            ),
          ],
        ),
      ),
    );
  }

  Widget _buildSummaryItem({
    required IconData icon,
    required String value,
    required String label,
    required Color color,
  }) {
    return Column(
      children: [
        Container(
          padding: const EdgeInsets.all(10),
          decoration: BoxDecoration(
            color: color.withOpacity(0.2),
            borderRadius: BorderRadius.circular(12),
          ),
          child: Icon(icon, color: color, size: 24),
        ),
        const SizedBox(height: 8),
        Text(
          value,
          style: const TextStyle(
            color: Colors.white,
            fontWeight: FontWeight.w700,
            fontSize: 18,
          ),
        ),
        Text(label, style: TextStyle(color: AppTheme.mediumGray, fontSize: 14)),
      ],
    );
  }

  Widget _buildGroceryList() {
    return ListView.builder(
      padding: const EdgeInsets.fromLTRB(
        16,
        0,
        16,
        100,
      ), // Extra bottom padding for FAB
      itemCount: _groceryCategories.length,
      itemBuilder: (context, index) {
        final category = _groceryCategories.keys.elementAt(index);
        final items = _groceryCategories[category]!;
        final isExpanded = _expandedCategories[category] ?? false;

        return Column(
          crossAxisAlignment: CrossAxisAlignment.start,
          children: [
            // Category header
            InkWell(
              onTap: () => _toggleCategory(category),
              child: Padding(
                padding: const EdgeInsets.symmetric(vertical: 12),
                child: Row(
                  children: [
                    Icon(
                      isExpanded ? Icons.arrow_drop_down : Icons.arrow_right,
                      color: AppTheme.primaryOrange,
                    ),
                    const SizedBox(width: 8),
                    Text(
                      category,
                      style: const TextStyle(
                        color: Colors.white,
                        fontWeight: FontWeight.w600,
                        fontSize: 18,
                      ),
                    ),
                    const SizedBox(width: 8),
                    Text(
                      '(${items.length})',
                      style: TextStyle(
                        color: AppTheme.mediumGray,
                        fontSize: 16,
                      ),
                    ),
                  ],
                ),
              ),
            ),

            // Category items
            if (isExpanded)
              ...List.generate(items.length, (itemIndex) {
                final item = items[itemIndex];
                final isChecked = item['isChecked'] as bool;

                return Container(
                  margin: const EdgeInsets.only(bottom: 12, left: 24),
                  padding: const EdgeInsets.all(16),
                  decoration: BoxDecoration(
                    color: AppTheme.slate,
                    borderRadius: BorderRadius.circular(16),
                    border: Border.all(
                      color: isChecked
                          ? Colors.green.withOpacity(0.3)
                          : Colors.transparent,
                      width: 1.5,
                    ),
                  ),
                  child: Row(
                    children: [
                      // Checkbox
                      InkWell(
                        onTap: () => _toggleItemChecked(category, itemIndex),
                        borderRadius: BorderRadius.circular(8),
                        child: Container(
                          width: 24,
                          height: 24,
                          decoration: BoxDecoration(
                            color: isChecked
                                ? Colors.green
                                : Colors.transparent,
                            borderRadius: BorderRadius.circular(8),
                            border: Border.all(
                              color: isChecked
                                  ? Colors.green
                                  : AppTheme.mediumGray,
                              width: 2,
                            ),
                          ),
                          child: isChecked
                              ? const Icon(
                                  Icons.check,
                                  color: Colors.white,
                                  size: 16,
                                )
                              : null,
                        ),
                      ),
                      const SizedBox(width: 16),

                      // Item details
                      Expanded(
                        child: Column(
                          crossAxisAlignment: CrossAxisAlignment.start,
                          children: [
                            Text(
                              item['name'] as String,
                              style: TextStyle(
                                color: isChecked
                                    ? AppTheme.mediumGray
                                    : Colors.white,
                                fontWeight: FontWeight.w600,
                                fontSize: 16,
                                decoration: isChecked
                                    ? TextDecoration.lineThrough
                                    : null,
                              ),
                            ),
                            const SizedBox(height: 4),
                            Row(
                              children: [
                                Text(
                                  item['quantity'] as String,
                                  style: TextStyle(
                                    color: AppTheme.mediumGray,
                                    fontSize: 14,
                                  ),
                                ),
                                const SizedBox(width: 16),
                                Container(
                                  padding: const EdgeInsets.symmetric(
                                    horizontal: 8,
                                    vertical: 2,
                                  ),
                                  decoration: BoxDecoration(
                                    color: AppTheme.primaryOrange.withOpacity(
                                      0.2,
                                    ),
                                    borderRadius: BorderRadius.circular(4),
                                  ),
                                  child: Text(
                                    item['bestStore'] as String,
                                    style: const TextStyle(
                                      color: AppTheme.primaryOrange,
                                      fontSize: 12,
                                      fontWeight: FontWeight.w500,
                                    ),
                                  ),
                                ),
                              ],
                            ),
                          ],
                        ),
                      ),

                      // Price
                      Text(
                        '\${(item['price'] as double).toStringAsFixed(2)}',
                        style: const TextStyle(
                          color: Colors.white,
                          fontWeight: FontWeight.w600,
                          fontSize: 16,
                        ),
                      ),
                    ],
                  ),
                );
              }),

            if (index < _groceryCategories.length - 1)
              const Divider(color: AppTheme.slate),
          ],
        );
      },
    );
  }

  Widget _buildEmptyState() {
    return Center(
      child: Column(
        mainAxisAlignment: MainAxisAlignment.center,
        children: [
          Icon(
            Icons.shopping_cart_outlined,
            size: 80,
            color: AppTheme.mediumGray.withOpacity(0.5),
          ),
          const SizedBox(height: 16),
          const Text(
            'Your grocery list is empty',
            style: TextStyle(
              color: Colors.white,
              fontSize: 20,
              fontWeight: FontWeight.w600,
            ),
          ),
          const SizedBox(height: 8),
          Text(
            'Add items or generate from your meal plan',
            style: TextStyle(color: AppTheme.mediumGray, fontSize: 16),
          ),
          const SizedBox(height: 32),
          ElevatedButton.icon(
            onPressed: () {
              // TODO: Navigate to meal planning page
            },
            icon: const Icon(Icons.add),
            label: const Text('Add Items'),
            style: ElevatedButton.styleFrom(
              padding: const EdgeInsets.symmetric(horizontal: 24, vertical: 12),
            ),
          ),
        ],
      ),
    );
  }
}

</file>
<file path="lib/features/meal_planning/presentation/pages/meal_plan_page.dart">
import 'package:flutter/material.dart';
import '../../../../core/theme/app_theme.dart';
import '../../../../core/routes/app_router.dart';
import '../widgets/meal_card.dart';
import '../../../recipes/data/models/recipe_model.dart';

/// Meal planning page
class MealPlanPage extends StatefulWidget {
  const MealPlanPage({super.key});

  @override
  State<MealPlanPage> createState() => _MealPlanPageState();
}

class _MealPlanPageState extends State<MealPlanPage> {
  int _selectedDayIndex = 0;
  final List<String> _weekDays = [
    'Monday',
    'Tuesday',
    'Wednesday',
    'Thursday',
    'Friday',
    'Saturday',
    'Sunday',
  ];

  final Map<String, String> _mealTypes = {
    'breakfast': 'Breakfast',
    'lunch': 'Lunch',
    'dinner': 'Dinner',
    'snack': 'Snack',
  };

  @override
  Widget build(BuildContext context) {
    return Scaffold(
      backgroundColor: AppTheme.darkNavy,
      appBar: AppBar(
        backgroundColor: AppTheme.darkNavy,
        elevation: 0,
        title: const Text('Meal Plan'),
        actions: [
          IconButton(
            icon: const Icon(Icons.add),
            onPressed: () {
              // TODO: Implement add meal functionality
            },
          ),
        ],
      ),
      body: Column(
        crossAxisAlignment: CrossAxisAlignment.start,
        children: [
          const SizedBox(height: 16),
          _buildDaySelector(),
          const SizedBox(height: 24),
          Expanded(child: _buildMealList()),
        ],
      ),
      floatingActionButton: FloatingActionButton.extended(
        backgroundColor: AppTheme.primaryOrange,
        icon: const Icon(Icons.refresh),
        label: const Text('Regenerate'),
        onPressed: () {
          // TODO: Implement regenerate plan functionality
        },
      ),
    );
  }

  Widget _buildDaySelector() {
    return SizedBox(
      height: 40,
      child: ListView.builder(
        scrollDirection: Axis.horizontal,
        itemCount: _weekDays.length,
        padding: const EdgeInsets.symmetric(horizontal: 16),
        itemBuilder: (context, index) {
          final isSelected = index == _selectedDayIndex;
          return Padding(
            padding: const EdgeInsets.only(right: 8),
            child: Material(
              color: isSelected ? AppTheme.primaryOrange : Colors.transparent,
              borderRadius: BorderRadius.circular(20),
              child: InkWell(
                onTap: () => setState(() => _selectedDayIndex = index),
                borderRadius: BorderRadius.circular(20),
                child: Container(
                  padding: const EdgeInsets.symmetric(horizontal: 16),
                  alignment: Alignment.center,
                  decoration: BoxDecoration(
                    border: !isSelected
                        ? Border.all(
                            color: AppTheme.mediumGray.withOpacity(0.3),
                          )
                        : null,
                    borderRadius: BorderRadius.circular(20),
                  ),
                  child: Text(
                    _weekDays[index],
                    style: TextStyle(
                      color: isSelected
                          ? AppTheme.white
                          : AppTheme.white.withOpacity(0.7),
                      fontWeight: isSelected
                          ? FontWeight.w600
                          : FontWeight.normal,
                    ),
                  ),
                ),
              ),
            ),
          );
        },
      ),
    );
  }

  Widget _buildMealList() {
    // This would come from your data source in a real app
    final mockRecipes = RecipeModel.getMockRecipes();

    return ListView.builder(
      padding: const EdgeInsets.symmetric(horizontal: 16),
      itemCount: _mealTypes.length,
      itemBuilder: (context, index) {
        final mealType = _mealTypes.keys.elementAt(index);
        final mealName = _mealTypes.values.elementAt(index);
        // In a real app, you would get the recipe for this meal slot from your data source
        final recipe = mockRecipes[index % mockRecipes.length];

        return Padding(
          padding: const EdgeInsets.only(bottom: 16),
          child: MealCard(
            recipe: recipe,
            mealType: mealName,
            onTap: () {
              Navigator.pushNamed(
                context,
                AppRouter.recipeDetail,
                arguments: {'recipeId': recipe.id},
              );
            },
          ),
        );
      },
    );
  }
}

</file>
<file path="lib/features/meal_planning/presentation/widgets/meal_card.dart">
import 'package:flutter/material.dart';
import 'package:cached_network_image/cached_network_image.dart';
import '../../../../core/theme/app_theme.dart';
import '../../../recipes/domain/entities/recipe.dart';

class MealCard extends StatelessWidget {
  final Recipe recipe;
  final String mealType;
  final VoidCallback onTap;

  const MealCard({
    super.key,
    required this.recipe,
    required this.mealType,
    required this.onTap,
  });

  @override
  Widget build(BuildContext context) {
    return Card(
      elevation: 0,
      color: AppTheme.charcoal,
      shape: RoundedRectangleBorder(borderRadius: BorderRadius.circular(20)),
      child: InkWell(
        onTap: onTap,
        borderRadius: BorderRadius.circular(20),
        child: Column(
          crossAxisAlignment: CrossAxisAlignment.start,
          children: [
            // Image container with gradient overlay
            ClipRRect(
              borderRadius: const BorderRadius.vertical(
                top: Radius.circular(20),
              ),
              child: Stack(
                children: [
                  // Image
                  AspectRatio(
                    aspectRatio: 16 / 9,
                    child: CachedNetworkImage(
                      imageUrl:
                          recipe.imageUrl ??
                          'https://via.placeholder.com/400x300/16213E/FFFFFF?text=No+Image',
                      fit: BoxFit.cover,
                      placeholder: (context, url) => Container(
                        color: AppTheme.slate,
                        child: Center(
                          child: CircularProgressIndicator(
                            color: AppTheme.primaryOrange,
                          ),
                        ),
                      ),
                      errorWidget: (context, url, error) => Container(
                        color: AppTheme.slate,
                        child: Icon(
                          Icons.restaurant,
                          color: AppTheme.mediumGray,
                          size: 32,
                        ),
                      ),
                    ),
                  ),
                  // Gradient overlay
                  Positioned.fill(
                    child: DecoratedBox(
                      decoration: BoxDecoration(
                        gradient: LinearGradient(
                          begin: Alignment.topCenter,
                          end: Alignment.bottomCenter,
                          colors: [
                            Colors.transparent,
                            Colors.black.withOpacity(0.7),
                          ],
                          stops: const [0.5, 1.0],
                        ),
                      ),
                    ),
                  ),
                  // Meal type chip
                  Positioned(
                    top: 12,
                    left: 12,
                    child: Container(
                      padding: const EdgeInsets.symmetric(
                        horizontal: 12,
                        vertical: 6,
                      ),
                      decoration: BoxDecoration(
                        color: AppTheme.primaryOrange.withOpacity(0.9),
                        borderRadius: BorderRadius.circular(20),
                      ),
                      child: Text(
                        mealType,
                        style: TextStyle(
                          color: AppTheme.white,
                          fontSize: 12,
                          fontWeight: FontWeight.w600,
                        ),
                      ),
                    ),
                  ),
                ],
              ),
            ),
            // Content padding
            Padding(
              padding: const EdgeInsets.all(16),
              child: Column(
                crossAxisAlignment: CrossAxisAlignment.start,
                children: [
                  Text(
                    recipe.name,
                    style: AppTheme.h3.copyWith(color: AppTheme.white),
                    maxLines: 2,
                    overflow: TextOverflow.ellipsis,
                  ),
                  const SizedBox(height: 8),
                  Row(
                    children: [
                      _buildInfoChip(
                        Icons.access_time,
                        '${recipe.totalTimeMinutes} min',
                      ),
                      const SizedBox(width: 12),
                      _buildInfoChip(
                        Icons.local_fire_department,
                        '${recipe.nutrition.calories} cal',
                      ),
                    ],
                  ),
                ],
              ),
            ),
          ],
        ),
      ),
    );
  }

  Widget _buildInfoChip(IconData icon, String label) {
    return Container(
      padding: const EdgeInsets.symmetric(horizontal: 8, vertical: 4),
      decoration: BoxDecoration(
        color: AppTheme.darkNavy.withOpacity(0.5),
        borderRadius: BorderRadius.circular(12),
      ),
      child: Row(
        mainAxisSize: MainAxisSize.min,
        children: [
          Icon(icon, size: 14, color: AppTheme.mediumGray),
          const SizedBox(width: 4),
          Text(
            label,
            style: TextStyle(color: AppTheme.mediumGray, fontSize: 12),
          ),
        ],
      ),
    );
  }
}

</file>
<file path="lib/features/nutrition/data/models/nutrition_model.dart">
/// Model representing nutritional information
class NutritionModel {
  final int calories;
  final NutrientModel protein;
  final NutrientModel fat;
  final FatBreakdownModel fatBreakdown;
  final NutrientModel carbs;
  final NutrientModel fiber;
  final NutrientModel sugar;
  final NutrientModel sodium;
  final NutrientModel cholesterol;
  final VitaminModel vitamins;
  final MineralModel minerals;
  final List<String> dietLabels;
  final List<String> healthLabels;
  final List<String> cautions;
  final int servingWeight;
  final int servings;

  NutritionModel({
    required this.calories,
    required this.protein,
    required this.fat,
    required this.fatBreakdown,
    required this.carbs,
    required this.fiber,
    required this.sugar,
    required this.sodium,
    required this.cholesterol,
    required this.vitamins,
    required this.minerals,
    required this.dietLabels,
    required this.healthLabels,
    required this.cautions,
    required this.servingWeight,
    required this.servings,
  });

  factory NutritionModel.fromJson(Map<String, dynamic> json) {
    return NutritionModel(
      calories: json['calories'],
      protein: NutrientModel.fromJson(json['protein']),
      fat: NutrientModel.fromJson(json['fat']),
      fatBreakdown: json['fat']['breakdown'] != null
          ? FatBreakdownModel.fromJson(json['fat']['breakdown'])
          : FatBreakdownModel.empty(),
      carbs: NutrientModel.fromJson(json['carbs']),
      fiber: NutrientModel.fromJson(json['fiber']),
      sugar: NutrientModel.fromJson(json['sugar']),
      sodium: NutrientModel.fromJson(json['sodium']),
      cholesterol: NutrientModel.fromJson(json['cholesterol']),
      vitamins: VitaminModel.fromJson(json['vitamins']),
      minerals: MineralModel.fromJson(json['minerals']),
      dietLabels: List<String>.from(json['dietLabels'] ?? []),
      healthLabels: List<String>.from(json['healthLabels'] ?? []),
      cautions: List<String>.from(json['cautions'] ?? []),
      servingWeight: json['servingWeight'],
      servings: json['servings'],
    );
  }

  Map<String, dynamic> toJson() {
    return {
      'calories': calories,
      'protein': protein.toJson(),
      'fat': fat.toJson(),
      'carbs': carbs.toJson(),
      'fiber': fiber.toJson(),
      'sugar': sugar.toJson(),
      'sodium': sodium.toJson(),
      'cholesterol': cholesterol.toJson(),
      'vitamins': vitamins.toJson(),
      'minerals': minerals.toJson(),
      'dietLabels': dietLabels,
      'healthLabels': healthLabels,
      'cautions': cautions,
      'servingWeight': servingWeight,
      'servings': servings,
    };
  }
}

/// Model for a specific nutrient value with unit and optional daily value
class NutrientModel {
  final double value;
  final String unit;
  final String? label;
  final double? dailyValue;

  NutrientModel({
    required this.value,
    required this.unit,
    this.label,
    this.dailyValue,
  });

  factory NutrientModel.fromJson(Map<String, dynamic> json) {
    return NutrientModel(
      value: json['value'].toDouble(),
      unit: json['unit'],
      label: json['label'],
      dailyValue: json['dailyValue']?.toDouble(),
    );
  }

  Map<String, dynamic> toJson() {
    return {
      'value': value,
      'unit': unit,
      'label': label,
      'dailyValue': dailyValue,
    };
  }
}

/// Model for detailed fat breakdown
class FatBreakdownModel {
  final NutrientModel saturated;
  final NutrientModel polyunsaturated;
  final NutrientModel monounsaturated;
  final NutrientModel trans;
  final NutrientModel omega3;
  final NutrientModel omega6;

  FatBreakdownModel({
    required this.saturated,
    required this.polyunsaturated,
    required this.monounsaturated,
    required this.trans,
    required this.omega3,
    required this.omega6,
  });

  factory FatBreakdownModel.fromJson(Map<String, dynamic> json) {
    return FatBreakdownModel(
      saturated: NutrientModel.fromJson(json['saturated']),
      polyunsaturated: NutrientModel.fromJson(json['polyunsaturated']),
      monounsaturated: NutrientModel.fromJson(json['monounsaturated']),
      trans: NutrientModel.fromJson(json['trans']),
      omega3: NutrientModel.fromJson(json['omega3']),
      omega6: NutrientModel.fromJson(json['omega6']),
    );
  }

  factory FatBreakdownModel.empty() {
    return FatBreakdownModel(
      saturated: NutrientModel(value: 0, unit: 'g', label: 'Saturated Fat'),
      polyunsaturated: NutrientModel(
        value: 0,
        unit: 'g',
        label: 'Polyunsaturated Fat',
      ),
      monounsaturated: NutrientModel(
        value: 0,
        unit: 'g',
        label: 'Monounsaturated Fat',
      ),
      trans: NutrientModel(value: 0, unit: 'g', label: 'Trans Fat'),
      omega3: NutrientModel(value: 0, unit: 'g', label: 'Omega-3'),
      omega6: NutrientModel(value: 0, unit: 'g', label: 'Omega-6'),
    );
  }

  Map<String, dynamic> toJson() {
    return {
      'saturated': saturated.toJson(),
      'polyunsaturated': polyunsaturated.toJson(),
      'monounsaturated': monounsaturated.toJson(),
      'trans': trans.toJson(),
      'omega3': omega3.toJson(),
      'omega6': omega6.toJson(),
    };
  }
}

/// Model for vitamin data
class VitaminModel {
  final NutrientModel a;
  final NutrientModel c;
  final NutrientModel d;
  final NutrientModel e;
  final NutrientModel k;
  final NutrientModel b1;
  final NutrientModel b2;
  final NutrientModel b3;
  final NutrientModel b6;
  final NutrientModel b12;
  final NutrientModel folate;

  VitaminModel({
    required this.a,
    required this.c,
    required this.d,
    required this.e,
    required this.k,
    required this.b1,
    required this.b2,
    required this.b3,
    required this.b6,
    required this.b12,
    required this.folate,
  });

  factory VitaminModel.fromJson(Map<String, dynamic> json) {
    return VitaminModel(
      a: NutrientModel.fromJson(json['A']),
      c: NutrientModel.fromJson(json['C']),
      d: NutrientModel.fromJson(json['D']),
      e: NutrientModel.fromJson(json['E']),
      k: NutrientModel.fromJson(json['K']),
      b1: NutrientModel.fromJson(json['B1']),
      b2: NutrientModel.fromJson(json['B2']),
      b3: NutrientModel.fromJson(json['B3']),
      b6: NutrientModel.fromJson(json['B6']),
      b12: NutrientModel.fromJson(json['B12']),
      folate: NutrientModel.fromJson(json['folate']),
    );
  }

  Map<String, dynamic> toJson() {
    return {
      'A': a.toJson(),
      'C': c.toJson(),
      'D': d.toJson(),
      'E': e.toJson(),
      'K': k.toJson(),
      'B1': b1.toJson(),
      'B2': b2.toJson(),
      'B3': b3.toJson(),
      'B6': b6.toJson(),
      'B12': b12.toJson(),
      'folate': folate.toJson(),
    };
  }
}

/// Model for mineral data
class MineralModel {
  final NutrientModel calcium;
  final NutrientModel iron;
  final NutrientModel magnesium;
  final NutrientModel phosphorus;
  final NutrientModel potassium;
  final NutrientModel zinc;
  final NutrientModel selenium;

  MineralModel({
    required this.calcium,
    required this.iron,
    required this.magnesium,
    required this.phosphorus,
    required this.potassium,
    required this.zinc,
    required this.selenium,
  });

  factory MineralModel.fromJson(Map<String, dynamic> json) {
    return MineralModel(
      calcium: NutrientModel.fromJson(json['calcium']),
      iron: NutrientModel.fromJson(json['iron']),
      magnesium: NutrientModel.fromJson(json['magnesium']),
      phosphorus: NutrientModel.fromJson(json['phosphorus']),
      potassium: NutrientModel.fromJson(json['potassium']),
      zinc: NutrientModel.fromJson(json['zinc']),
      selenium: NutrientModel.fromJson(json['selenium']),
    );
  }

  Map<String, dynamic> toJson() {
    return {
      'calcium': calcium.toJson(),
      'iron': iron.toJson(),
      'magnesium': magnesium.toJson(),
      'phosphorus': phosphorus.toJson(),
      'potassium': potassium.toJson(),
      'zinc': zinc.toJson(),
      'selenium': selenium.toJson(),
    };
  }
}

</file>
<file path="lib/features/nutrition/data/repositories/nutrition_repository_impl.dart">
import 'package:dartz/dartz.dart';

import '../../../../core/error/failure.dart';
import '../../domain/repositories/nutrition_repository.dart';
import '../models/nutrition_model.dart';
import '../services/nutrition_service.dart';

class NutritionRepositoryImpl implements NutritionRepository {
  final NutritionService _nutritionService;

  NutritionRepositoryImpl({required NutritionService nutritionService})
    : _nutritionService = nutritionService;

  @override
  Future<Either<Failure, NutritionModel>> getNutritionInfo({
    required List<String> ingredients,
    required int servings,
  }) async {
    try {
      final result = await _nutritionService.getNutritionInfo(
        ingredients: ingredients,
        servings: servings,
      );
      return Right(result);
    } on Exception catch (e) {
      return Left(ServerFailure(e.toString()));
    }
  }
}

</file>
<file path="lib/features/nutrition/data/services/nutrition_service.dart">
import 'dart:convert';
import 'package:supabase_flutter/supabase_flutter.dart';
import '../../../../core/network/supabase_service.dart';
import '../models/nutrition_model.dart';

/// Service for accessing nutrition data from the API
class NutritionService {
  final SupabaseClient _client = SupabaseService.client;

  /// Get nutrition information for a list of ingredients
  Future<NutritionModel> getNutritionInfo({
    required List<String> ingredients,
    required int servings,
  }) async {
    try {
      final response = await _client.functions.invoke(
        'get-nutrition',
        body: jsonEncode({'ingredients': ingredients, 'servings': servings}),
      );

      if (response.status != 200) {
        throw Exception('Error getting nutrition data: ${response.data}');
      }

      return NutritionModel.fromJson(response.data);
    } catch (e) {
      throw Exception('Failed to get nutrition data: $e');
    }
  }
}

</file>
<file path="lib/features/nutrition/domain/repositories/nutrition_repository.dart">
import 'package:dartz/dartz.dart';

import '../../../../core/error/failure.dart';
import '../../data/models/nutrition_model.dart';

abstract class NutritionRepository {
  /// Gets nutrition information for a list of ingredients
  Future<Either<Failure, NutritionModel>> getNutritionInfo({
    required List<String> ingredients,
    required int servings,
  });
}

</file>
<file path="lib/features/nutrition/domain/usecases/get_nutrition_info.dart">
import 'package:dartz/dartz.dart';
import 'package:equatable/equatable.dart';

import '../../../../core/error/failure.dart';
import '../../../../core/usecases/usecase.dart';
import '../repositories/nutrition_repository.dart';
import '../../data/models/nutrition_model.dart';

/// Usecase to get nutrition information for ingredients
class GetNutritionInfo implements UseCase<NutritionModel, NutritionParams> {
  final NutritionRepository repository;

  GetNutritionInfo(this.repository);

  @override
  Future<Either<Failure, NutritionModel>> call(NutritionParams params) {
    return repository.getNutritionInfo(
      ingredients: params.ingredients,
      servings: params.servings,
    );
  }
}

/// Parameters for GetNutritionInfo usecase
class NutritionParams extends Equatable {
  final List<String> ingredients;
  final int servings;

  const NutritionParams({required this.ingredients, required this.servings});

  @override
  List<Object> get props => [ingredients, servings];
}

</file>
<file path="lib/features/nutrition/presentation/bloc/nutrition_bloc.dart">
import 'package:equatable/equatable.dart';
import 'package:flutter_bloc/flutter_bloc.dart';

import '../../data/models/nutrition_model.dart';
import '../../domain/usecases/get_nutrition_info.dart';

part 'nutrition_event.dart';
part 'nutrition_state.dart';

/// BLoC to manage nutrition information
class NutritionBloc extends Bloc<NutritionEvent, NutritionState> {
  final GetNutritionInfo getNutritionInfo;

  NutritionBloc({required this.getNutritionInfo}) : super(NutritionInitial()) {
    on<GetNutritionForIngredients>(_onGetNutrition);
  }

  Future<void> _onGetNutrition(
    GetNutritionForIngredients event,
    Emitter<NutritionState> emit,
  ) async {
    emit(NutritionLoading());

    final result = await getNutritionInfo(
      NutritionParams(ingredients: event.ingredients, servings: event.servings),
    );

    result.fold(
      (failure) => emit(NutritionError(message: failure.message)),
      (nutrition) => emit(NutritionLoaded(nutrition: nutrition)),
    );
  }
}

</file>
<file path="lib/features/nutrition/presentation/bloc/nutrition_event.dart">
part of 'nutrition_bloc.dart';

/// Events for the nutrition bloc
abstract class NutritionEvent extends Equatable {
  @override
  List<Object> get props => [];
}

/// Event to get nutrition info for ingredients
class GetNutritionForIngredients extends NutritionEvent {
  final List<String> ingredients;
  final int servings;

  GetNutritionForIngredients({
    required this.ingredients,
    required this.servings,
  });

  @override
  List<Object> get props => [ingredients, servings];
}

</file>
<file path="lib/features/nutrition/presentation/bloc/nutrition_state.dart">
part of 'nutrition_bloc.dart';

/// States for the nutrition bloc
abstract class NutritionState extends Equatable {
  @override
  List<Object> get props => [];
}

/// Initial state
class NutritionInitial extends NutritionState {}

/// Loading state
class NutritionLoading extends NutritionState {}

/// State when nutrition data is successfully loaded
class NutritionLoaded extends NutritionState {
  final NutritionModel nutrition;

  NutritionLoaded({required this.nutrition});

  @override
  List<Object> get props => [nutrition];
}

/// Error state
class NutritionError extends NutritionState {
  final String message;

  NutritionError({required this.message});

  @override
  List<Object> get props => [message];
}

</file>
<file path="lib/features/nutrition/presentation/pages/nutrition_page.dart">
import 'package:flutter/material.dart';
import 'package:flutter_bloc/flutter_bloc.dart';

import '../../../../core/di/injection_container.dart' as di;
import '../../../../core/theme/app_theme.dart';
import '../bloc/nutrition_bloc.dart';
import '../widgets/nutrition_info_widget.dart';

/// A page to display nutrition information for a list of ingredients
class NutritionPage extends StatelessWidget {
  final List<String> ingredients;
  final int servings;
  final String title;

  const NutritionPage({
    super.key,
    required this.ingredients,
    required this.servings,
    this.title = 'Nutrition Information',
  });

  @override
  Widget build(BuildContext context) {
    return BlocProvider(
      create: (_) => di.sl<NutritionBloc>(),
      child: Scaffold(
        appBar: AppBar(
          title: Text(title),
          backgroundColor: AppTheme.darkNavy,
          elevation: 0,
        ),
        backgroundColor: AppTheme.darkNavy,
        body: SafeArea(
          child: SingleChildScrollView(
            padding: const EdgeInsets.all(16),
            child: Column(
              crossAxisAlignment: CrossAxisAlignment.start,
              children: [
                // Ingredients list header
                Text(
                  'Ingredients',
                  style: AppTheme.h3.copyWith(color: AppTheme.white),
                ),
                const SizedBox(height: 16),
                // Ingredients list
                ..._buildIngredientsList(),
                const SizedBox(height: 24),
                // Nutrition info
                NutritionInfoWidget(
                  ingredients: ingredients,
                  servings: servings,
                ),
                const SizedBox(height: 16),
                // Serving info
                Row(
                  mainAxisAlignment: MainAxisAlignment.center,
                  children: [
                    Text(
                      'Analysis based on $servings serving${servings > 1 ? 's' : ''}',
                      style: AppTheme.caption.copyWith(
                        color: AppTheme.mediumGray,
                      ),
                    ),
                  ],
                ),
                const SizedBox(height: 24),
              ],
            ),
          ),
        ),
      ),
    );
  }

  List<Widget> _buildIngredientsList() {
    return ingredients.map((ingredient) {
      return Padding(
        padding: const EdgeInsets.only(bottom: 8),
        child: Row(
          crossAxisAlignment: CrossAxisAlignment.start,
          children: [
            const Icon(Icons.circle, size: 8, color: AppTheme.primaryOrange),
            const SizedBox(width: 8),
            Expanded(
              child: Text(
                ingredient,
                style: AppTheme.body.copyWith(color: AppTheme.lightGray),
              ),
            ),
          ],
        ),
      );
    }).toList();
  }
}

</file>
<file path="lib/features/nutrition/presentation/widgets/nutrition_bottom_sheet.dart">
import 'package:flutter/material.dart';
import '../../../../core/theme/app_theme.dart';
import '../../data/models/nutrition_model.dart';

/// A fancy bottom sheet to display nutrition information
class NutritionBottomSheet extends StatelessWidget {
  final NutritionModel nutrition;
  final String title;

  const NutritionBottomSheet({
    super.key,
    required this.nutrition,
    required this.title,
  });

  @override
  Widget build(BuildContext context) {
    return DraggableScrollableSheet(
      initialChildSize: 0.9,
      minChildSize: 0.5,
      maxChildSize: 0.95,
      builder: (context, scrollController) {
        return Container(
          decoration: BoxDecoration(
            color: AppTheme.darkNavy,
            borderRadius: const BorderRadius.vertical(top: Radius.circular(24)),
            boxShadow: [
              BoxShadow(
                color: Colors.black.withOpacity(0.2),
                blurRadius: 20,
                offset: const Offset(0, -5),
              ),
            ],
          ),
          child: Column(
            children: [
              _buildHandle(),
              _buildHeader(context),
              Expanded(
                child: Theme(
                  data: Theme.of(
                    context,
                  ).copyWith(shadowColor: Colors.transparent),
                  child: ListView(
                    controller: scrollController,
                    padding: const EdgeInsets.fromLTRB(16, 0, 16, 32),
                    children: [
                      _buildMacronutrients(context),
                      const SizedBox(height: 32),
                      _buildSectionWithDivider(
                        context,
                        'Fat Breakdown',
                        _buildFatBreakdown(context),
                      ),
                      const SizedBox(height: 32),
                      _buildSectionWithDivider(
                        context,
                        'Vitamins',
                        _buildVitamins(context),
                      ),
                      const SizedBox(height: 32),
                      _buildSectionWithDivider(
                        context,
                        'Minerals',
                        _buildMinerals(context),
                      ),
                      const SizedBox(height: 32),
                      _buildLabelsAndCautions(context),
                    ],
                  ),
                ),
              ),
            ],
          ),
        );
      },
    );
  }

  Widget _buildHandle() {
    return Container(
      padding: const EdgeInsets.symmetric(vertical: 12),
      child: Container(
        width: 40,
        height: 4,
        decoration: BoxDecoration(
          color: AppTheme.mediumGray.withOpacity(0.3),
          borderRadius: BorderRadius.circular(2),
        ),
      ),
    );
  }

  Widget _buildHeader(BuildContext context) {
    return Container(
      padding: const EdgeInsets.fromLTRB(24, 0, 24, 24),
      child: Column(
        children: [
          Text(
            title,
            style: AppTheme.h2.copyWith(color: AppTheme.white),
            textAlign: TextAlign.center,
          ),
          const SizedBox(height: 8),
          Text(
            'Per serving (${nutrition.servingWeight}g)',
            style: AppTheme.body.copyWith(color: AppTheme.mediumGray),
          ),
          const SizedBox(height: 8),
          Row(
            mainAxisAlignment: MainAxisAlignment.center,
            children: [
              Text(
                '${nutrition.calories}',
                style: AppTheme.h1.copyWith(
                  color: AppTheme.primaryOrange,
                  fontSize: 48,
                  height: 1,
                ),
              ),
              const SizedBox(width: 8),
              Text(
                'calories',
                style: AppTheme.body.copyWith(color: AppTheme.primaryOrange),
              ),
            ],
          ),
        ],
      ),
    );
  }

  Widget _buildMacronutrients(BuildContext context) {
    final macros = [
      (nutrition.protein, Icons.fitness_center, AppTheme.primaryGreen),
      (nutrition.fat, Icons.oil_barrel, AppTheme.primaryOrange),
      (nutrition.carbs, Icons.grain, AppTheme.primaryCoral),
    ];

    return Row(
      children: macros.map((m) {
        return Expanded(
          child: Padding(
            padding: const EdgeInsets.symmetric(horizontal: 6),
            child: _buildNutrientCard(m.$1, icon: m.$2, color: m.$3),
          ),
        );
      }).toList(),
    );
  }

  Widget _buildSectionWithDivider(
    BuildContext context,
    String title,
    Widget content,
  ) {
    return Column(
      crossAxisAlignment: CrossAxisAlignment.start,
      children: [
        Row(
          children: [
            Text(
              title,
              style: AppTheme.h3.copyWith(color: AppTheme.white, fontSize: 20),
            ),
            const SizedBox(width: 12),
            Expanded(
              child: Container(
                height: 1,
                decoration: BoxDecoration(
                  gradient: LinearGradient(
                    colors: [
                      AppTheme.mediumGray.withOpacity(0.3),
                      AppTheme.mediumGray.withOpacity(0.1),
                    ],
                  ),
                ),
              ),
            ),
          ],
        ),
        const SizedBox(height: 16),
        content,
      ],
    );
  }

  Widget _buildFatBreakdown(BuildContext context) {
    return Column(
      children: [
        _buildNutrientRow(nutrition.fatBreakdown.saturated),
        _buildNutrientRow(nutrition.fatBreakdown.polyunsaturated),
        _buildNutrientRow(nutrition.fatBreakdown.monounsaturated),
        _buildNutrientRow(nutrition.fatBreakdown.trans),
        _buildNutrientRow(nutrition.fatBreakdown.omega3),
        _buildNutrientRow(nutrition.fatBreakdown.omega6),
      ],
    );
  }

  Widget _buildVitamins(BuildContext context) {
    return Column(
      children: [
        _buildNutrientRow(nutrition.vitamins.a),
        _buildNutrientRow(nutrition.vitamins.c),
        _buildNutrientRow(nutrition.vitamins.d),
        _buildNutrientRow(nutrition.vitamins.e),
        _buildNutrientRow(nutrition.vitamins.k),
        _buildNutrientRow(nutrition.vitamins.b1),
        _buildNutrientRow(nutrition.vitamins.b2),
        _buildNutrientRow(nutrition.vitamins.b3),
        _buildNutrientRow(nutrition.vitamins.b6),
        _buildNutrientRow(nutrition.vitamins.b12),
        _buildNutrientRow(nutrition.vitamins.folate),
      ],
    );
  }

  Widget _buildMinerals(BuildContext context) {
    return Column(
      children: [
        _buildNutrientRow(nutrition.minerals.calcium),
        _buildNutrientRow(nutrition.minerals.iron),
        _buildNutrientRow(nutrition.minerals.magnesium),
        _buildNutrientRow(nutrition.minerals.phosphorus),
        _buildNutrientRow(nutrition.minerals.potassium),
        _buildNutrientRow(nutrition.minerals.zinc),
        _buildNutrientRow(nutrition.minerals.selenium),
      ],
    );
  }

  Widget _buildNutrientCard(
    NutrientModel nutrient, {
    IconData? icon,
    Color? color,
  }) {
    return Container(
      padding: const EdgeInsets.symmetric(horizontal: 16, vertical: 20),
      decoration: BoxDecoration(
        color: AppTheme.charcoal,
        borderRadius: BorderRadius.circular(20),
        boxShadow: [
          BoxShadow(
            color: Colors.black.withOpacity(0.1),
            blurRadius: 10,
            offset: const Offset(0, 4),
          ),
        ],
      ),
      child: Column(
        children: [
          if (icon != null) ...[
            Icon(icon, color: color ?? AppTheme.primaryOrange, size: 28),
            const SizedBox(height: 12),
          ],
          Text(
            '${nutrient.value}${nutrient.unit}',
            style: AppTheme.h3.copyWith(
              color: color ?? AppTheme.white,
              fontSize: 24,
              height: 1,
            ),
          ),
          const SizedBox(height: 4),
          Text(
            nutrient.label ?? '',
            style: AppTheme.body.copyWith(
              color: AppTheme.mediumGray,
              fontSize: 14,
            ),
            textAlign: TextAlign.center,
          ),
          if (nutrient.dailyValue != null) ...[
            const SizedBox(height: 8),
            Container(
              padding: const EdgeInsets.symmetric(horizontal: 8, vertical: 2),
              decoration: BoxDecoration(
                color: (color ?? AppTheme.primaryOrange).withOpacity(0.1),
                borderRadius: BorderRadius.circular(12),
              ),
              child: Text(
                '${nutrient.dailyValue}% DV',
                style: AppTheme.body.copyWith(
                  color: color ?? AppTheme.primaryOrange,
                  fontSize: 12,
                  fontWeight: FontWeight.w600,
                ),
              ),
            ),
          ],
        ],
      ),
    );
  }

  Widget _buildNutrientRow(NutrientModel nutrient) {
    return Padding(
      padding: const EdgeInsets.symmetric(vertical: 8),
      child: Row(
        children: [
          Expanded(
            flex: 2,
            child: Text(
              nutrient.label ?? '',
              style: AppTheme.body.copyWith(color: AppTheme.white),
            ),
          ),
          const SizedBox(width: 16),
          Text(
            '${nutrient.value}${nutrient.unit}',
            style: AppTheme.body.copyWith(color: AppTheme.white),
          ),
          if (nutrient.dailyValue != null) ...[
            const SizedBox(width: 16),
            Container(
              width: 70,
              padding: const EdgeInsets.symmetric(horizontal: 8, vertical: 2),
              decoration: BoxDecoration(
                color: AppTheme.primaryOrange.withOpacity(0.1),
                borderRadius: BorderRadius.circular(12),
              ),
              child: Text(
                '${nutrient.dailyValue}% DV',
                style: AppTheme.body.copyWith(
                  color: AppTheme.primaryOrange,
                  fontSize: 12,
                  fontWeight: FontWeight.w600,
                ),
                textAlign: TextAlign.center,
              ),
            ),
          ],
        ],
      ),
    );
  }

  Widget _buildLabelsAndCautions(BuildContext context) {
    return Column(
      crossAxisAlignment: CrossAxisAlignment.start,
      children: [
        if (nutrition.dietLabels.isNotEmpty) ...[
          Text('Diet', style: AppTheme.h3),
          const SizedBox(height: 12),
          Wrap(
            spacing: 8,
            runSpacing: 8,
            children: nutrition.dietLabels.map((label) {
              return _buildChip(
                label,
                AppTheme.successGreen,
                Icons.check_circle_outline,
              );
            }).toList(),
          ),
          const SizedBox(height: 24),
        ],
        if (nutrition.healthLabels.isNotEmpty) ...[
          Text('Health', style: AppTheme.h3),
          const SizedBox(height: 12),
          Wrap(
            spacing: 8,
            runSpacing: 8,
            children: nutrition.healthLabels.map((label) {
              return _buildChip(
                label,
                AppTheme.accentBlue,
                Icons.favorite_border,
              );
            }).toList(),
          ),
          const SizedBox(height: 24),
        ],
        if (nutrition.cautions.isNotEmpty) ...[
          Text('Cautions', style: AppTheme.h3),
          const SizedBox(height: 12),
          Wrap(
            spacing: 8,
            runSpacing: 8,
            children: nutrition.cautions.map((caution) {
              return _buildChip(
                caution,
                AppTheme.accentYellow,
                Icons.warning_amber_outlined,
              );
            }).toList(),
          ),
        ],
      ],
    );
  }

  Widget _buildChip(String label, Color color, IconData icon) {
    return Container(
      padding: const EdgeInsets.symmetric(horizontal: 12, vertical: 8),
      decoration: BoxDecoration(
        color: color.withOpacity(0.1),
        borderRadius: BorderRadius.circular(20),
        border: Border.all(color: color.withOpacity(0.3), width: 1),
      ),
      child: Row(
        mainAxisSize: MainAxisSize.min,
        children: [
          Icon(icon, size: 16, color: color),
          const SizedBox(width: 6),
          Text(
            label,
            style: AppTheme.body.copyWith(
              color: color,
              fontSize: 14,
              fontWeight: FontWeight.w500,
            ),
          ),
        ],
      ),
    );
  }
}

</file>
<file path="lib/features/nutrition/presentation/widgets/nutrition_info_widget.dart">
import 'package:flutter/material.dart';
import 'package:flutter_bloc/flutter_bloc.dart';
import '../../../../core/theme/app_theme.dart';
import '../../data/models/nutrition_model.dart';
import '../bloc/nutrition_bloc.dart';

class NutritionInfoWidget extends StatelessWidget {
  final NutritionModel nutrition;

  const NutritionInfoWidget({super.key, required this.nutrition});

  @override
  Widget build(BuildContext context) {
    return Container(
      padding: const EdgeInsets.all(16),
      decoration: BoxDecoration(
        color: AppTheme.charcoal,
        borderRadius: BorderRadius.circular(20),
      ),
      child: Column(
        crossAxisAlignment: CrossAxisAlignment.start,
        children: [
          Row(
            mainAxisAlignment: MainAxisAlignment.spaceAround,
            children: [
              _buildNutrientCard(
                context,
                nutrition.protein,
                Icons.fitness_center,
                AppTheme.accentPurple,
              ),
              _buildNutrientCard(
                context,
                nutrition.carbs,
                Icons.grain,
                AppTheme.primaryCoral,
              ),
              _buildNutrientCard(
                context,
                nutrition.fat,
                Icons.oil_barrel,
                AppTheme.primaryPeach,
              ),
            ],
          ),
          const SizedBox(height: 16),
          _buildNutrientRow('Calories', '${nutrition.calories} kcal'),
          _buildNutrientRow(
            'Fiber',
            '${nutrition.fiber.value}${nutrition.fiber.unit}',
            dailyValue: nutrition.fiber.dailyValue,
          ),
          _buildNutrientRow(
            'Sugar',
            '${nutrition.sugar.value}${nutrition.sugar.unit}',
          ),
          _buildNutrientRow(
            'Sodium',
            '${nutrition.sodium.value}${nutrition.sodium.unit}',
            dailyValue: nutrition.sodium.dailyValue,
          ),
        ],
      ),
    );
  }

  Widget _buildNutrientCard(
    BuildContext context,
    NutrientModel nutrient,
    IconData icon,
    Color color,
  ) {
    return Container(
      width: 100,
      padding: const EdgeInsets.all(12),
      decoration: BoxDecoration(
        color: color.withOpacity(0.1),
        borderRadius: BorderRadius.circular(16),
        border: Border.all(color: color.withOpacity(0.3), width: 1),
      ),
      child: Column(
        mainAxisSize: MainAxisSize.min,
        children: [
          Icon(icon, color: color),
          const SizedBox(height: 8),
          Text(
            '${nutrient.value}${nutrient.unit}',
            style: AppTheme.h3.copyWith(color: AppTheme.white, fontSize: 18),
          ),
          const SizedBox(height: 4),
          Text(
            nutrient.label ?? '',
            style: AppTheme.body.copyWith(
              color: AppTheme.mediumGray,
              fontSize: 14,
            ),
            textAlign: TextAlign.center,
          ),
          if (nutrient.dailyValue != null) ...[
            const SizedBox(height: 4),
            Text(
              '${nutrient.dailyValue}% DV',
              style: AppTheme.body.copyWith(
                color: color,
                fontSize: 12,
                fontWeight: FontWeight.w600,
              ),
            ),
          ],
        ],
      ),
    );
  }

  Widget _buildNutrientRow(String label, String value, {double? dailyValue}) {
    return Padding(
      padding: const EdgeInsets.symmetric(vertical: 4),
      child: Row(
        children: [
          Text(label, style: AppTheme.body),
          const Spacer(),
          Text(
            value,
            style: AppTheme.body.copyWith(
              color: AppTheme.white,
              fontWeight: FontWeight.w500,
            ),
          ),
          if (dailyValue != null) ...[
            const SizedBox(width: 8),
            Text(
              '$dailyValue% DV',
              style: AppTheme.body.copyWith(
                color: AppTheme.mediumGray,
                fontSize: 14,
              ),
            ),
          ],
        ],
      ),
    );
  }
}

</file>
<file path="lib/features/onboarding/data/repositories/user_profile_repository.dart">
import 'package:hive_flutter/hive_flutter.dart';
import '../../domain/entities/user_profile.dart';

/// Repository to manage user profile data
class UserProfileRepository {
  static const String _profileBoxName = 'user_profile_box';
  static const String _profileKey = 'user_profile';

  /// Initialize the repository
  Future<void> init() async {
    await Hive.openBox(_profileBoxName);
  }

  /// Get the user profile
  Future<UserProfile> getUserProfile() async {
    final box = Hive.box(_profileBoxName);
    final profileData = box.get(_profileKey);

    if (profileData == null) {
      return UserProfile.empty();
    }

    return UserProfile(
      name: profileData['name'],
      dietaryPreferences: List<String>.from(
        profileData['dietaryPreferences'] ?? [],
      ),
      allergies: List<String>.from(profileData['allergies'] ?? []),
      nutritionalGoal: profileData['nutritionalGoal'] ?? '',
      targetCalories: profileData['targetCalories'] ?? 2000,
      targetMacros: Map<String, int>.from(
        profileData['targetMacros'] ??
            {'protein': 100, 'carbs': 250, 'fat': 65},
      ),
      householdSize: profileData['householdSize'] ?? 1,
      onboardingCompleted: profileData['onboardingCompleted'] ?? false,
    );
  }

  /// Save the user profile
  Future<void> saveUserProfile(UserProfile profile) async {
    final box = Hive.box(_profileBoxName);

    await box.put(_profileKey, {
      'name': profile.name,
      'dietaryPreferences': profile.dietaryPreferences,
      'allergies': profile.allergies,
      'nutritionalGoal': profile.nutritionalGoal,
      'targetCalories': profile.targetCalories,
      'targetMacros': profile.targetMacros,
      'householdSize': profile.householdSize,
      'onboardingCompleted': profile.onboardingCompleted,
    });
  }

  /// Check if onboarding has been completed
  Future<bool> isOnboardingCompleted() async {
    final profile = await getUserProfile();
    return profile.onboardingCompleted;
  }

  /// Mark onboarding as completed
  Future<void> completeOnboarding() async {
    final profile = await getUserProfile();
    await saveUserProfile(profile.copyWith(onboardingCompleted: true));
  }
}

</file>
<file path="lib/features/onboarding/domain/entities/user_profile.dart">
import 'package:equatable/equatable.dart';

/// User dietary profile information
class UserProfile extends Equatable {
  /// User name (optional)
  final String? name;

  /// Dietary preferences (e.g., vegetarian, vegan, etc.)
  final List<String> dietaryPreferences;

  /// Food allergies or restrictions
  final List<String> allergies;

  /// Nutritional goals like weight loss, muscle gain, etc.
  final String nutritionalGoal;

  /// Target daily caloric intake
  final int targetCalories;

  /// Target daily macros
  final Map<String, int> targetMacros;

  /// Household size (number of people)
  final int householdSize;

  /// Has completed onboarding process
  final bool onboardingCompleted;

  /// Constructor
  const UserProfile({
    this.name,
    this.dietaryPreferences = const [],
    this.allergies = const [],
    this.nutritionalGoal = '',
    this.targetCalories = 2000,
    this.targetMacros = const {'protein': 100, 'carbs': 250, 'fat': 65},
    this.householdSize = 1,
    this.onboardingCompleted = false,
  });

  /// Create a copy with new values
  UserProfile copyWith({
    String? name,
    List<String>? dietaryPreferences,
    List<String>? allergies,
    String? nutritionalGoal,
    int? targetCalories,
    Map<String, int>? targetMacros,
    int? householdSize,
    bool? onboardingCompleted,
  }) {
    return UserProfile(
      name: name ?? this.name,
      dietaryPreferences: dietaryPreferences ?? this.dietaryPreferences,
      allergies: allergies ?? this.allergies,
      nutritionalGoal: nutritionalGoal ?? this.nutritionalGoal,
      targetCalories: targetCalories ?? this.targetCalories,
      targetMacros: targetMacros ?? this.targetMacros,
      householdSize: householdSize ?? this.householdSize,
      onboardingCompleted: onboardingCompleted ?? this.onboardingCompleted,
    );
  }

  /// Empty profile
  static UserProfile empty() => const UserProfile();

  @override
  List<Object?> get props => [
    name,
    dietaryPreferences,
    allergies,
    nutritionalGoal,
    targetCalories,
    targetMacros,
    householdSize,
    onboardingCompleted,
  ];
}

</file>
<file path="lib/features/onboarding/presentation/pages/allergies_page.dart">
import 'package:flutter/material.dart';
import '../../../../core/theme/app_theme.dart';
import '../../domain/entities/user_profile.dart';
import '../widgets/onboarding_button.dart';
import '../widgets/selection_chip.dart';

/// Page to collect allergy and restriction information
class AllergiesPage extends StatefulWidget {
  final UserProfile profile;
  final Function(UserProfile) onProfileUpdated;
  final VoidCallback onNext;
  final VoidCallback onBack;

  const AllergiesPage({
    super.key,
    required this.profile,
    required this.onProfileUpdated,
    required this.onNext,
    required this.onBack,
  });

  @override
  State<AllergiesPage> createState() => _AllergiesPageState();
}

class _AllergiesPageState extends State<AllergiesPage> {
  // Common allergies and restrictions
  final List<String> _commonAllergies = [
    'Dairy',
    'Eggs',
    'Fish',
    'Shellfish',
    'Tree Nuts',
    'Peanuts',
    'Wheat',
    'Soy',
    'Gluten',
    'Sesame',
  ];

  // Selected allergies
  late List<String> _selectedAllergies;
  final TextEditingController _otherAllergyController = TextEditingController();

  @override
  void initState() {
    super.initState();
    // Initialize from profile
    _selectedAllergies = List<String>.from(widget.profile.allergies);
  }

  @override
  void dispose() {
    _otherAllergyController.dispose();
    super.dispose();
  }

  void _toggleAllergy(String allergy) {
    setState(() {
      if (_selectedAllergies.contains(allergy)) {
        _selectedAllergies.remove(allergy);
      } else {
        _selectedAllergies.add(allergy);
      }
    });
  }

  void _addCustomAllergy() {
    final allergy = _otherAllergyController.text.trim();
    if (allergy.isNotEmpty && !_selectedAllergies.contains(allergy)) {
      setState(() {
        _selectedAllergies.add(allergy);
        _otherAllergyController.clear();
      });
    }
  }

  void _proceedToNext() {
    // Update profile with allergies
    final updatedProfile = widget.profile.copyWith(
      allergies: _selectedAllergies,
    );

    // Notify parent about update
    widget.onProfileUpdated(updatedProfile);

    // Navigate to next page
    widget.onNext();
  }

  @override
  Widget build(BuildContext context) {
    return SingleChildScrollView(
      child: Padding(
        padding: const EdgeInsets.all(24),
        child: Column(
          crossAxisAlignment: CrossAxisAlignment.start,
          children: [
            // Back button and title
            Row(
              children: [
                OnboardingBackButton(onPressed: widget.onBack),
                Expanded(
                  child: Text(
                    'Allergies & Restrictions',
                    style: TextStyle(
                      color: AppTheme.white,
                      fontSize: 22,
                      fontWeight: FontWeight.bold,
                    ),
                  ),
                ),
              ],
            ),
            const SizedBox(height: 8),
            Padding(
              padding: const EdgeInsets.only(left: 48),
              child: Text(
                'Select any allergies or dietary restrictions you have.',
                style: TextStyle(color: AppTheme.mediumGray, fontSize: 14),
              ),
            ),
            const SizedBox(height: 24),

            Text(
              'Common Allergies',
              style: TextStyle(
                color: AppTheme.white,
                fontSize: 16,
                fontWeight: FontWeight.w600,
              ),
            ),
            const SizedBox(height: 16),

            // Scrollable allergies list as chips
            Wrap(
              spacing: 8,
              runSpacing: 8,
              children: _commonAllergies.map((allergy) {
                final isSelected = _selectedAllergies.contains(allergy);

                return SelectionChip(
                  label: allergy,
                  isSelected: isSelected,
                  onTap: () => _toggleAllergy(allergy),
                );
              }).toList(),
            ),

            const SizedBox(height: 24),
            Text(
              'Add Other Allergies',
              style: TextStyle(
                color: AppTheme.white,
                fontSize: 16,
                fontWeight: FontWeight.w600,
              ),
            ),
            const SizedBox(height: 12),

            // Custom allergy input
            Row(
              children: [
                Expanded(
                  child: TextField(
                    controller: _otherAllergyController,
                    style: TextStyle(color: AppTheme.white),
                    textInputAction: TextInputAction.done,
                    onSubmitted: (_) {
                      _addCustomAllergy();
                    },
                    decoration: InputDecoration(
                      hintText: 'E.g., Avocado',
                      hintStyle: TextStyle(color: AppTheme.mediumGray),
                      filled: true,
                      fillColor: AppTheme.slate,
                      border: OutlineInputBorder(
                        borderRadius: BorderRadius.circular(12),
                        borderSide: BorderSide.none,
                      ),
                      contentPadding: const EdgeInsets.symmetric(
                        horizontal: 16,
                        vertical: 14,
                      ),
                    ),
                  ),
                ),
                const SizedBox(width: 8),
                InkWell(
                  onTap: _addCustomAllergy,
                  borderRadius: BorderRadius.circular(12),
                  child: Container(
                    padding: const EdgeInsets.all(14),
                    decoration: BoxDecoration(
                      color: AppTheme.primaryOrange,
                      borderRadius: BorderRadius.circular(12),
                    ),
                    child: Icon(Icons.add, color: AppTheme.darkNavy),
                  ),
                ),
              ],
            ),

            const SizedBox(height: 16),

            // Selected allergies section
            if (_selectedAllergies.isNotEmpty) ...[
              Text(
                'Your Selected Allergies:',
                style: TextStyle(
                  color: AppTheme.white,
                  fontSize: 16,
                  fontWeight: FontWeight.w600,
                ),
              ),
              const SizedBox(height: 8),
              Wrap(
                spacing: 8,
                runSpacing: 8,
                children: _selectedAllergies.map((allergy) {
                  return Chip(
                    label: Text(allergy),
                    backgroundColor: AppTheme.primaryOrange,
                    labelStyle: TextStyle(color: AppTheme.darkNavy),
                    deleteIconColor: AppTheme.darkNavy,
                    onDeleted: () => _toggleAllergy(allergy),
                  );
                }).toList(),
              ),
            ],

            const SizedBox(height: 24),

            // Next button
            OnboardingButton(
              label: 'Continue',
              onPressed: _proceedToNext,
              icon: Icons.arrow_forward,
            ),
            const SizedBox(height: 16), // Add bottom padding
          ],
        ),
      ),
    );
  }
}

</file>
<file path="lib/features/onboarding/presentation/pages/dietary_preferences_page.dart">
import 'package:flutter/material.dart';
import '../../../../core/theme/app_theme.dart';
import '../../domain/entities/user_profile.dart';
import '../widgets/onboarding_button.dart';
import '../widgets/selection_card.dart';

/// Page to collect dietary preferences information
class DietaryPreferencesPage extends StatefulWidget {
  final UserProfile profile;
  final Function(UserProfile) onProfileUpdated;
  final VoidCallback onNext;
  final VoidCallback onBack;

  const DietaryPreferencesPage({
    super.key,
    required this.profile,
    required this.onProfileUpdated,
    required this.onNext,
    required this.onBack,
  });

  @override
  State<DietaryPreferencesPage> createState() => _DietaryPreferencesPageState();
}

class _DietaryPreferencesPageState extends State<DietaryPreferencesPage> {
  // Available dietary preference options
  final List<Map<String, dynamic>> _dietaryOptions = [
    {
      'title': 'No Restrictions',
      'icon': Icons.restaurant,
      'description': 'I eat everything',
    },
    {
      'title': 'Vegetarian',
      'icon': Icons.spa,
      'description': 'No meat or fish, but dairy and eggs are ok',
    },
    {
      'title': 'Vegan',
      'icon': Icons.eco,
      'description': 'No animal products including dairy and eggs',
    },
    {
      'title': 'Pescatarian',
      'icon': Icons.set_meal,
      'description': 'No meat, but fish and seafood are ok',
    },
    {
      'title': 'Keto',
      'icon': Icons.fastfood,
      'description': 'Low carbs, high fat and protein',
    },
    {
      'title': 'Paleo',
      'icon': Icons.fitness_center,
      'description': 'Focuses on whole foods, avoids processed foods',
    },
  ];

  // Selected preferences
  late List<String> _selectedPreferences;
  bool get _hasSelection => _selectedPreferences.isNotEmpty;

  @override
  void initState() {
    super.initState();
    // Initialize selected preferences from profile
    _selectedPreferences = List<String>.from(widget.profile.dietaryPreferences);
  }

  void _togglePreference(String preference) {
    setState(() {
      if (_selectedPreferences.contains(preference)) {
        _selectedPreferences.remove(preference);
      } else {
        _selectedPreferences.add(preference);
      }
    });
  }

  void _proceedToNext() {
    // Update the profile with selected preferences
    final updatedProfile = widget.profile.copyWith(
      dietaryPreferences: _selectedPreferences,
    );

    // Notify parent about the update
    widget.onProfileUpdated(updatedProfile);

    // Navigate to next page
    widget.onNext();
  }

  @override
  Widget build(BuildContext context) {
    return Padding(
      padding: const EdgeInsets.all(24),
      child: Column(
        crossAxisAlignment: CrossAxisAlignment.start,
        children: [
          // Back button and title
          Row(
            children: [
              OnboardingBackButton(onPressed: widget.onBack),
              Text(
                'Dietary Preferences',
                style: TextStyle(
                  color: AppTheme.white,
                  fontSize: 22,
                  fontWeight: FontWeight.bold,
                ),
              ),
            ],
          ),
          const SizedBox(height: 8),
          Padding(
            padding: const EdgeInsets.only(left: 48),
            child: Text(
              'Select your dietary preferences to help us create personalized meal plans.',
              style: TextStyle(color: AppTheme.mediumGray, fontSize: 14),
            ),
          ),
          const SizedBox(height: 24),

          // Scrollable preferences list
          Expanded(
            child: ListView.builder(
              itemCount: _dietaryOptions.length,
              itemBuilder: (context, index) {
                final option = _dietaryOptions[index];
                final isSelected = _selectedPreferences.contains(
                  option['title'],
                );

                return SelectionCard(
                  title: option['title'],
                  icon: option['icon'],
                  description: option['description'],
                  isSelected: isSelected,
                  onTap: () => _togglePreference(option['title']),
                );
              },
            ),
          ),

          const SizedBox(height: 24),

          // Next button
          OnboardingButton(
            label: 'Continue',
            onPressed: _proceedToNext,
            isEnabled: _hasSelection,
            icon: Icons.arrow_forward,
          ),
        ],
      ),
    );
  }
}

</file>
<file path="lib/features/onboarding/presentation/pages/household_page.dart">
import 'package:flutter/material.dart';
import 'package:flutter/services.dart';
import '../../../../core/theme/app_theme.dart';
import '../../domain/entities/user_profile.dart';
import '../widgets/onboarding_button.dart';

/// Page to collect household information
class HouseholdPage extends StatefulWidget {
  final UserProfile profile;
  final Function(UserProfile) onProfileUpdated;
  final VoidCallback onNext;
  final VoidCallback onBack;

  const HouseholdPage({
    super.key,
    required this.profile,
    required this.onProfileUpdated,
    required this.onNext,
    required this.onBack,
  });

  @override
  State<HouseholdPage> createState() => _HouseholdPageState();
}

class _HouseholdPageState extends State<HouseholdPage> {
  late int _householdSize;
  final TextEditingController _householdController = TextEditingController();

  // Whether to generate meals for everyone or just the user
  bool _mealsForAll = true;

  // Person name
  final TextEditingController _nameController = TextEditingController();

  @override
  void initState() {
    super.initState();

    // Initialize from profile
    _householdSize = widget.profile.householdSize;
    _householdController.text = _householdSize.toString();

    // Set name if available
    _nameController.text = widget.profile.name ?? '';
  }

  @override
  void dispose() {
    _householdController.dispose();
    _nameController.dispose();
    super.dispose();
  }

  void _incrementHousehold() {
    setState(() {
      _householdSize++;
      _householdController.text = _householdSize.toString();
    });
  }

  void _decrementHousehold() {
    if (_householdSize > 1) {
      setState(() {
        _householdSize--;
        _householdController.text = _householdSize.toString();
      });
    }
  }

  void _toggleMealsForAll(bool value) {
    setState(() {
      _mealsForAll = value;
    });
  }

  void _updateHousehold(String value) {
    if (value.isNotEmpty) {
      final size = int.tryParse(value);
      if (size != null && size > 0) {
        setState(() {
          _householdSize = size;
        });
      } else {
        _householdController.text = _householdSize.toString();
      }
    }
  }

  bool get _isValid => _householdSize > 0;

  void _proceedToNext() {
    // Update profile
    final updatedProfile = widget.profile.copyWith(
      name: _nameController.text.isNotEmpty ? _nameController.text : null,
      householdSize: _householdSize,
    );

    // Notify parent
    widget.onProfileUpdated(updatedProfile);

    // Navigate to next page
    widget.onNext();
  }

  @override
  Widget build(BuildContext context) {
    return Padding(
      padding: const EdgeInsets.all(24),
      child: Column(
        crossAxisAlignment: CrossAxisAlignment.start,
        children: [
          // Back button and title
          Row(
            children: [
              OnboardingBackButton(onPressed: widget.onBack),
              Text(
                'Household Information',
                style: TextStyle(
                  color: AppTheme.white,
                  fontSize: 22,
                  fontWeight: FontWeight.bold,
                ),
              ),
            ],
          ),
          const SizedBox(height: 8),
          Padding(
            padding: const EdgeInsets.only(left: 48),
            child: Text(
              'Tell us about your household to personalize portion sizes.',
              style: TextStyle(color: AppTheme.mediumGray, fontSize: 14),
            ),
          ),
          const SizedBox(height: 32),

          // First name input (optional)
          Text(
            'Your First Name (Optional)',
            style: TextStyle(
              color: AppTheme.white,
              fontSize: 16,
              fontWeight: FontWeight.w600,
            ),
          ),
          const SizedBox(height: 12),
          TextField(
            controller: _nameController,
            style: TextStyle(color: AppTheme.white),
            decoration: InputDecoration(
              hintText: 'Enter your name',
              hintStyle: TextStyle(color: AppTheme.mediumGray),
              filled: true,
              fillColor: AppTheme.slate,
              border: OutlineInputBorder(
                borderRadius: BorderRadius.circular(12),
                borderSide: BorderSide.none,
              ),
              contentPadding: const EdgeInsets.symmetric(
                horizontal: 16,
                vertical: 14,
              ),
            ),
          ),
          const SizedBox(height: 32),

          // Household size
          Text(
            'Household Size',
            style: TextStyle(
              color: AppTheme.white,
              fontSize: 16,
              fontWeight: FontWeight.w600,
            ),
          ),
          const SizedBox(height: 8),
          Text(
            'How many people will you be cooking for?',
            style: TextStyle(color: AppTheme.mediumGray, fontSize: 14),
          ),
          const SizedBox(height: 16),
          Row(
            children: [
              // Decrement button
              _buildCounterButton(
                icon: Icons.remove,
                onPressed: _decrementHousehold,
                isEnabled: _householdSize > 1,
              ),
              const SizedBox(width: 16),

              // Counter input field
              Expanded(
                child: TextField(
                  controller: _householdController,
                  keyboardType: TextInputType.number,
                  inputFormatters: [FilteringTextInputFormatter.digitsOnly],
                  textAlign: TextAlign.center,
                  onChanged: _updateHousehold,
                  style: TextStyle(
                    color: AppTheme.white,
                    fontSize: 20,
                    fontWeight: FontWeight.bold,
                  ),
                  decoration: InputDecoration(
                    filled: true,
                    fillColor: AppTheme.slate,
                    border: OutlineInputBorder(
                      borderRadius: BorderRadius.circular(12),
                      borderSide: BorderSide.none,
                    ),
                    contentPadding: const EdgeInsets.symmetric(vertical: 16),
                  ),
                ),
              ),
              const SizedBox(width: 16),

              // Increment button
              _buildCounterButton(
                icon: Icons.add,
                onPressed: _incrementHousehold,
              ),
            ],
          ),
          const SizedBox(height: 16),

          // Person or people label
          Center(
            child: Text(
              _householdSize == 1 ? 'Just me' : '$_householdSize people',
              style: TextStyle(color: AppTheme.mediumGray, fontSize: 16),
            ),
          ),

          const SizedBox(height: 32),

          // Generate meals for all switch
          Container(
            padding: const EdgeInsets.all(16),
            decoration: BoxDecoration(
              color: AppTheme.slate,
              borderRadius: BorderRadius.circular(12),
            ),
            child: Row(
              children: [
                Expanded(
                  child: Column(
                    crossAxisAlignment: CrossAxisAlignment.start,
                    children: [
                      Text(
                        'Generate Meals For All',
                        style: TextStyle(
                          color: AppTheme.white,
                          fontSize: 16,
                          fontWeight: FontWeight.w600,
                        ),
                      ),
                      const SizedBox(height: 4),
                      Text(
                        'Automatically adjust recipes for your household size',
                        style: TextStyle(
                          color: AppTheme.mediumGray,
                          fontSize: 14,
                        ),
                      ),
                    ],
                  ),
                ),
                Switch(
                  value: _mealsForAll,
                  onChanged: _toggleMealsForAll,
                  activeColor: AppTheme.primaryOrange,
                  activeTrackColor: AppTheme.primaryOrange.withOpacity(0.3),
                ),
              ],
            ),
          ),

          const Spacer(),

          // Continue button
          OnboardingButton(
            label: 'Continue',
            onPressed: _proceedToNext,
            isEnabled: _isValid,
            icon: Icons.arrow_forward,
          ),
        ],
      ),
    );
  }

  Widget _buildCounterButton({
    required IconData icon,
    required VoidCallback onPressed,
    bool isEnabled = true,
  }) {
    return InkWell(
      onTap: isEnabled ? onPressed : null,
      borderRadius: BorderRadius.circular(12),
      child: Container(
        width: 50,
        height: 50,
        decoration: BoxDecoration(
          color: isEnabled ? AppTheme.primaryOrange : AppTheme.slate,
          borderRadius: BorderRadius.circular(12),
        ),
        child: Icon(
          icon,
          color: isEnabled ? AppTheme.darkNavy : AppTheme.mediumGray,
        ),
      ),
    );
  }
}

</file>
<file path="lib/features/onboarding/presentation/pages/nutrition_goals_page.dart">
import 'package:flutter/material.dart';
import 'package:flutter/services.dart';
import '../../../../core/theme/app_theme.dart';
import '../../domain/entities/user_profile.dart';
import '../widgets/onboarding_button.dart';
import '../widgets/selection_card.dart';

/// Page to collect nutritional goals information
class NutritionGoalsPage extends StatefulWidget {
  final UserProfile profile;
  final Function(UserProfile) onProfileUpdated;
  final VoidCallback onNext;
  final VoidCallback onBack;

  const NutritionGoalsPage({
    super.key,
    required this.profile,
    required this.onProfileUpdated,
    required this.onNext,
    required this.onBack,
  });

  @override
  State<NutritionGoalsPage> createState() => _NutritionGoalsPageState();
}

class _NutritionGoalsPageState extends State<NutritionGoalsPage> {
  // Available nutrition goal options
  final List<Map<String, dynamic>> _nutritionGoals = [
    {
      'title': 'Weight Loss',
      'icon': Icons.trending_down,
      'description': 'Reduce calorie intake for healthy weight loss',
    },
    {
      'title': 'Maintenance',
      'icon': Icons.balance,
      'description': 'Maintain current weight and balanced nutrition',
    },
    {
      'title': 'Muscle Gain',
      'icon': Icons.fitness_center,
      'description': 'Higher protein and calories for muscle building',
    },
    {
      'title': 'Balanced Diet',
      'icon': Icons.restaurant,
      'description': 'Focus on nutritional balance without specific goals',
    },
  ];

  // Selected goal and calorie info
  late String _selectedGoal;
  late int _targetCalories;
  final TextEditingController _caloriesController = TextEditingController();

  // Macros input controllers
  final TextEditingController _proteinController = TextEditingController();
  final TextEditingController _carbsController = TextEditingController();
  final TextEditingController _fatController = TextEditingController();

  @override
  void initState() {
    super.initState();

    // Initialize from profile
    _selectedGoal = widget.profile.nutritionalGoal;
    _targetCalories = widget.profile.targetCalories;

    // Set up controllers
    _caloriesController.text = _targetCalories.toString();

    _proteinController.text =
        widget.profile.targetMacros['protein']?.toString() ?? '100';
    _carbsController.text =
        widget.profile.targetMacros['carbs']?.toString() ?? '250';
    _fatController.text =
        widget.profile.targetMacros['fat']?.toString() ?? '65';
  }

  @override
  void dispose() {
    _caloriesController.dispose();
    _proteinController.dispose();
    _carbsController.dispose();
    _fatController.dispose();
    super.dispose();
  }

  void _selectGoal(String goal) {
    setState(() {
      _selectedGoal = goal;

      // Set suggested values based on goal
      switch (goal) {
        case 'Weight Loss':
          _targetCalories = 1800;
          _caloriesController.text = '1800';
          _proteinController.text = '120';
          _carbsController.text = '180';
          _fatController.text = '60';
          break;

        case 'Maintenance':
          _targetCalories = 2200;
          _caloriesController.text = '2200';
          _proteinController.text = '110';
          _carbsController.text = '275';
          _fatController.text = '73';
          break;

        case 'Muscle Gain':
          _targetCalories = 2500;
          _caloriesController.text = '2500';
          _proteinController.text = '150';
          _carbsController.text = '300';
          _fatController.text = '83';
          break;

        case 'Balanced Diet':
          _targetCalories = 2000;
          _caloriesController.text = '2000';
          _proteinController.text = '100';
          _carbsController.text = '250';
          _fatController.text = '65';
          break;
      }
    });
  }

  void _updateCalories(String value) {
    if (value.isNotEmpty) {
      setState(() {
        _targetCalories = int.tryParse(value) ?? _targetCalories;
      });
    }
  }

  bool get _isValid => _selectedGoal.isNotEmpty;

  void _proceedToNext() {
    // Get macro values
    final protein = int.tryParse(_proteinController.text) ?? 100;
    final carbs = int.tryParse(_carbsController.text) ?? 250;
    final fat = int.tryParse(_fatController.text) ?? 65;

    // Update profile
    final updatedProfile = widget.profile.copyWith(
      nutritionalGoal: _selectedGoal,
      targetCalories: _targetCalories,
      targetMacros: {'protein': protein, 'carbs': carbs, 'fat': fat},
    );

    // Notify parent
    widget.onProfileUpdated(updatedProfile);

    // Navigate to next page
    widget.onNext();
  }

  @override
  Widget build(BuildContext context) {
    return Padding(
      padding: const EdgeInsets.all(24),
      child: SingleChildScrollView(
        child: Column(
          crossAxisAlignment: CrossAxisAlignment.start,
          children: [
            // Back button and title
            Row(
              children: [
                OnboardingBackButton(onPressed: widget.onBack),
                Text(
                  'Nutritional Goals',
                  style: TextStyle(
                    color: AppTheme.white,
                    fontSize: 22,
                    fontWeight: FontWeight.bold,
                  ),
                ),
              ],
            ),
            const SizedBox(height: 8),
            Padding(
              padding: const EdgeInsets.only(left: 48),
              child: Text(
                'Select your nutritional goals to personalize your meal plans.',
                style: TextStyle(color: AppTheme.mediumGray, fontSize: 14),
              ),
            ),
            const SizedBox(height: 24),

            // Goal selection
            Text(
              'What is your main goal?',
              style: TextStyle(
                color: AppTheme.white,
                fontSize: 18,
                fontWeight: FontWeight.w600,
              ),
            ),
            const SizedBox(height: 16),

            // Goals list
            ...List.generate(_nutritionGoals.length, (index) {
              final goal = _nutritionGoals[index];
              final isSelected = _selectedGoal == goal['title'];

              return SelectionCard(
                title: goal['title'],
                icon: goal['icon'],
                description: goal['description'],
                isSelected: isSelected,
                onTap: () => _selectGoal(goal['title']),
              );
            }),

            const SizedBox(height: 32),

            // Calorie target
            Text(
              'Daily Calorie Target',
              style: TextStyle(
                color: AppTheme.white,
                fontSize: 18,
                fontWeight: FontWeight.w600,
              ),
            ),
            const SizedBox(height: 12),

            TextField(
              controller: _caloriesController,
              keyboardType: TextInputType.number,
              inputFormatters: [FilteringTextInputFormatter.digitsOnly],
              onChanged: _updateCalories,
              style: TextStyle(color: AppTheme.white),
              decoration: InputDecoration(
                hintText: '2000',
                hintStyle: TextStyle(color: AppTheme.mediumGray),
                filled: true,
                fillColor: AppTheme.slate,
                border: OutlineInputBorder(
                  borderRadius: BorderRadius.circular(12),
                  borderSide: BorderSide.none,
                ),
                suffixText: 'calories',
                suffixStyle: TextStyle(color: AppTheme.mediumGray),
                contentPadding: const EdgeInsets.symmetric(
                  horizontal: 16,
                  vertical: 14,
                ),
              ),
            ),

            const SizedBox(height: 32),

            // Macros section
            Text(
              'Macronutrient Targets',
              style: TextStyle(
                color: AppTheme.white,
                fontSize: 18,
                fontWeight: FontWeight.w600,
              ),
            ),
            const SizedBox(height: 16),

            // Protein
            _buildMacroInput(
              label: 'Protein',
              controller: _proteinController,
              color: AppTheme.accentPurple,
            ),

            const SizedBox(height: 12),

            // Carbs
            _buildMacroInput(
              label: 'Carbs',
              controller: _carbsController,
              color: AppTheme.primaryCoral,
            ),

            const SizedBox(height: 12),

            // Fat
            _buildMacroInput(
              label: 'Fat',
              controller: _fatController,
              color: AppTheme.primaryPeach,
            ),

            const SizedBox(height: 32),

            // Continue button
            OnboardingButton(
              label: 'Continue',
              onPressed: _proceedToNext,
              isEnabled: _isValid,
              icon: Icons.arrow_forward,
            ),

            const SizedBox(height: 40),
          ],
        ),
      ),
    );
  }

  Widget _buildMacroInput({
    required String label,
    required TextEditingController controller,
    required Color color,
  }) {
    return Row(
      children: [
        Container(
          width: 80,
          padding: const EdgeInsets.symmetric(vertical: 14),
          decoration: BoxDecoration(
            color: color.withOpacity(0.2),
            borderRadius: const BorderRadius.only(
              topLeft: Radius.circular(12),
              bottomLeft: Radius.circular(12),
            ),
          ),
          child: Center(
            child: Text(
              label,
              style: TextStyle(color: color, fontWeight: FontWeight.w600),
            ),
          ),
        ),
        Expanded(
          child: TextField(
            controller: controller,
            keyboardType: TextInputType.number,
            inputFormatters: [FilteringTextInputFormatter.digitsOnly],
            style: TextStyle(color: AppTheme.white),
            decoration: InputDecoration(
              filled: true,
              fillColor: AppTheme.slate,
              border: const OutlineInputBorder(
                borderRadius: BorderRadius.only(
                  topRight: Radius.circular(12),
                  bottomRight: Radius.circular(12),
                ),
                borderSide: BorderSide.none,
              ),
              suffixText: 'g',
              suffixStyle: TextStyle(color: AppTheme.mediumGray),
              contentPadding: const EdgeInsets.symmetric(
                horizontal: 16,
                vertical: 14,
              ),
            ),
          ),
        ),
      ],
    );
  }
}

</file>
<file path="lib/features/onboarding/presentation/pages/onboarding_page.dart">
import 'package:flutter/material.dart';
import '../../../../core/theme/app_theme.dart';
import '../../../../core/routes/app_router.dart';
import '../../data/repositories/user_profile_repository.dart';
import '../../domain/entities/user_profile.dart';
import '../widgets/onboarding_progress.dart';
import '../pages/welcome_page.dart';
import '../pages/dietary_preferences_page.dart';
import '../pages/allergies_page.dart';
import '../pages/nutrition_goals_page.dart';
import '../pages/household_page.dart';
import '../pages/summary_page.dart';

/// Main onboarding container that manages the onboarding steps
class OnboardingPage extends StatefulWidget {
  const OnboardingPage({super.key});

  @override
  State<OnboardingPage> createState() => _OnboardingPageState();
}

class _OnboardingPageState extends State<OnboardingPage> {
  final PageController _pageController = PageController();
  int _currentPage = 0;
  final UserProfileRepository _repository = UserProfileRepository();
  UserProfile? _profile;
  bool _isLoading = true;

  // Total number of onboarding steps
  final int _totalSteps = 5;

  @override
  void initState() {
    super.initState();
    _loadUserProfile();
  }

  Future<void> _loadUserProfile() async {
    setState(() => _isLoading = true);
    _profile = await _repository.getUserProfile();
    setState(() => _isLoading = false);
  }

  void _nextPage() {
    if (_currentPage < _totalSteps) {
      _pageController.nextPage(
        duration: const Duration(milliseconds: 400),
        curve: Curves.easeInOut,
      );
    }
  }

  void _previousPage() {
    if (_currentPage > 0) {
      _pageController.previousPage(
        duration: const Duration(milliseconds: 400),
        curve: Curves.easeInOut,
      );
    }
  }

  void _updateUserProfile(UserProfile updatedProfile) {
    setState(() {
      _profile = updatedProfile;
    });
  }

  void _completeOnboarding() async {
    // Mark onboarding as completed
    await _repository.saveUserProfile(
      _profile!.copyWith(onboardingCompleted: true),
    );

    if (!mounted) return;

    // Navigate to dashboard
    Navigator.of(context).pushReplacementNamed(AppRouter.dashboard);
  }

  void _skipToLastPage() {
    _pageController.animateToPage(
      _totalSteps,
      duration: const Duration(milliseconds: 600),
      curve: Curves.easeInOut,
    );
  }

  @override
  Widget build(BuildContext context) {
    if (_isLoading || _profile == null) {
      return Scaffold(
        backgroundColor: AppTheme.darkNavy,
        body: const Center(child: CircularProgressIndicator()),
      );
    }

    return Scaffold(
      backgroundColor: AppTheme.darkNavy,
      body: SafeArea(
        child: Column(
          children: [
            // Progress indicator
            Padding(
              padding: const EdgeInsets.symmetric(horizontal: 24, vertical: 16),
              child: OnboardingProgress(
                currentStep: _currentPage,
                totalSteps: _totalSteps + 1, // +1 for summary page
              ),
            ),

            // Page content
            Expanded(
              child: PageView(
                controller: _pageController,
                physics: const NeverScrollableScrollPhysics(),
                onPageChanged: (int page) {
                  setState(() {
                    _currentPage = page;
                  });
                },
                children: [
                  // Welcome page
                  WelcomePage(onNext: _nextPage),

                  // Dietary preferences
                  DietaryPreferencesPage(
                    profile: _profile!,
                    onProfileUpdated: _updateUserProfile,
                    onNext: _nextPage,
                    onBack: _previousPage,
                  ),

                  // Allergies and restrictions
                  AllergiesPage(
                    profile: _profile!,
                    onProfileUpdated: _updateUserProfile,
                    onNext: _nextPage,
                    onBack: _previousPage,
                  ),

                  // Nutrition goals
                  NutritionGoalsPage(
                    profile: _profile!,
                    onProfileUpdated: _updateUserProfile,
                    onNext: _nextPage,
                    onBack: _previousPage,
                  ),

                  // Household info
                  HouseholdPage(
                    profile: _profile!,
                    onProfileUpdated: _updateUserProfile,
                    onNext: _nextPage,
                    onBack: _previousPage,
                  ),

                  // Summary page
                  SummaryPage(
                    profile: _profile!,
                    onComplete: _completeOnboarding,
                    onBack: _previousPage,
                  ),
                ],
              ),
            ),

            // Skip button for testing (can be removed in production)
            if (_currentPage < _totalSteps)
              Padding(
                padding: const EdgeInsets.only(bottom: 16),
                child: TextButton(
                  onPressed: _skipToLastPage,
                  child: Text(
                    'Skip for now',
                    style: TextStyle(color: AppTheme.mediumGray, fontSize: 14),
                  ),
                ),
              ),
          ],
        ),
      ),
    );
  }
}

</file>
<file path="lib/features/onboarding/presentation/pages/splash_page.dart">
import 'package:flutter/material.dart';
import 'package:flutter/services.dart';
import '../../../../core/theme/app_theme.dart';
import '../../../../core/routes/app_router.dart';
import '../../data/repositories/user_profile_repository.dart';

/// A beautiful, intuitive splash screen that transitions to onboarding or dashboard
class SplashPage extends StatefulWidget {
  const SplashPage({super.key});

  @override
  State<SplashPage> createState() => _SplashPageState();
}

class _SplashPageState extends State<SplashPage>
    with SingleTickerProviderStateMixin {
  late AnimationController _animationController;
  late Animation<double> _fadeAnimation;
  late Animation<double> _scaleAnimation;
  late Animation<double> _pulseAnimation;
  final UserProfileRepository _repository = UserProfileRepository();

  @override
  void initState() {
    super.initState();

    // Set system UI overlay style for a cleaner look
    SystemChrome.setSystemUIOverlayStyle(
      const SystemUiOverlayStyle(
        statusBarColor: Colors.transparent,
        statusBarIconBrightness: Brightness.light,
      ),
    );

    // Initialize animations for more engaging experience
    _animationController = AnimationController(
      duration: const Duration(milliseconds: 1800),
      vsync: this,
    );

    _fadeAnimation = Tween<double>(begin: 0.0, end: 1.0).animate(
      CurvedAnimation(
        parent: _animationController,
        curve: const Interval(0.0, 0.65, curve: Curves.easeIn),
      ),
    );

    _scaleAnimation = Tween<double>(begin: 0.6, end: 1.0).animate(
      CurvedAnimation(
        parent: _animationController,
        curve: const Interval(0.0, 0.65, curve: Curves.easeOutBack),
      ),
    );

    _pulseAnimation =
        TweenSequence<double>([
          TweenSequenceItem(
            tween: Tween<double>(begin: 1.0, end: 1.08),
            weight: 1,
          ),
          TweenSequenceItem(
            tween: Tween<double>(begin: 1.08, end: 1.0),
            weight: 1,
          ),
        ]).animate(
          CurvedAnimation(
            parent: _animationController,
            curve: const Interval(0.65, 1.0, curve: Curves.easeInOut),
          ),
        );

    // Start animation
    _animationController.forward();

    // Check onboarding status and navigate accordingly
    _initializeAndCheckOnboarding();
  }

  Future<void> _initializeAndCheckOnboarding() async {
    try {
      // Initialize repository first
      await _repository.init();

      // Wait for animation
      await Future.delayed(const Duration(milliseconds: 2000));

      if (!mounted) return;

      // Check onboarding status
      final userProfile = await _repository.getUserProfile();
      final bool onboardingCompleted = userProfile.onboardingCompleted;

      if (onboardingCompleted) {
        // If onboarding completed, go to dashboard
        Navigator.of(context).pushReplacementNamed(AppRouter.dashboard);
      } else {
        // If onboarding not completed, go to onboarding
        Navigator.of(context).pushReplacementNamed(AppRouter.onboarding);
      }
    } catch (e) {
      // If there's an error, default to onboarding
      if (mounted) {
        Navigator.of(context).pushReplacementNamed(AppRouter.onboarding);
      }
    }
  }

  @override
  void dispose() {
    _animationController.dispose();
    super.dispose();
  }

  @override
  Widget build(BuildContext context) {
    return Scaffold(
      backgroundColor: AppTheme.darkNavy,
      body: SafeArea(
        child: Center(
          child: AnimatedBuilder(
            animation: _animationController,
            builder: (context, child) {
              return FadeTransition(
                opacity: _fadeAnimation,
                child: ScaleTransition(
                  scale: _scaleAnimation,
                  child: Transform.scale(
                    scale: _pulseAnimation.value,
                    child: Column(
                      mainAxisAlignment: MainAxisAlignment.center,
                      children: [
                        // App logo with pulsing effect
                        Container(
                          width: 130,
                          height: 130,
                          decoration: BoxDecoration(
                            color: AppTheme.primaryOrange,
                            borderRadius: BorderRadius.circular(36),
                            boxShadow: [
                              BoxShadow(
                                color: AppTheme.primaryOrange.withOpacity(0.3),
                                blurRadius: 25,
                                spreadRadius: 5,
                              ),
                            ],
                          ),
                          child: Icon(
                            Icons.restaurant_rounded,
                            size: 75,
                            color: AppTheme.darkNavy,
                          ),
                        ),
                        const SizedBox(height: 28),

                        // App name
                        Text(
                          'Foodster',
                          style: TextStyle(
                            fontSize: 36,
                            fontWeight: FontWeight.bold,
                            color: AppTheme.white,
                            letterSpacing: 1.2,
                          ),
                        ),

                        const SizedBox(height: 12),

                        // App tagline
                        Text(
                          'Your smart nutrition companion',
                          style: TextStyle(
                            fontSize: 16,
                            color: AppTheme.mediumGray,
                            letterSpacing: 0.5,
                          ),
                        ),

                        const SizedBox(height: 48),

                        // Loading indicator
                        const CircularProgressIndicator(
                          valueColor: AlwaysStoppedAnimation<Color>(
                            AppTheme.primaryOrange,
                          ),
                        ),
                      ],
                    ),
                  ),
                ),
              );
            },
          ),
        ),
      ),
    );
  }
}

</file>
<file path="lib/features/onboarding/presentation/pages/summary_page.dart">
import 'package:flutter/material.dart';
import '../../../../core/theme/app_theme.dart';
import '../../domain/entities/user_profile.dart';
import '../widgets/onboarding_button.dart';

/// Final onboarding page with profile summary
class SummaryPage extends StatelessWidget {
  final UserProfile profile;
  final VoidCallback onComplete;
  final VoidCallback onBack;

  const SummaryPage({
    super.key,
    required this.profile,
    required this.onComplete,
    required this.onBack,
  });

  @override
  Widget build(BuildContext context) {
    final String greeting = profile.name != null && profile.name!.isNotEmpty
        ? 'Great job, ${profile.name}!'
        : 'Great job!';

    return Padding(
      padding: const EdgeInsets.all(24),
      child: Column(
        crossAxisAlignment: CrossAxisAlignment.start,
        children: [
          // Back button and title
          Row(
            children: [
              OnboardingBackButton(onPressed: onBack),
              Text(
                'Your Profile Summary',
                style: TextStyle(
                  color: AppTheme.white,
                  fontSize: 22,
                  fontWeight: FontWeight.bold,
                ),
              ),
            ],
          ),
          const SizedBox(height: 32),

          // Completion message
          Text(
            greeting,
            style: TextStyle(
              color: AppTheme.white,
              fontSize: 28,
              fontWeight: FontWeight.bold,
            ),
          ),
          const SizedBox(height: 12),
          Text(
            'Here\'s a summary of your dietary profile. You can always change these settings later.',
            style: TextStyle(color: AppTheme.mediumGray, fontSize: 16),
          ),
          const SizedBox(height: 32),

          // Dietary preferences section
          _buildSummarySection(
            title: 'Dietary Preferences',
            items: profile.dietaryPreferences,
            icon: Icons.restaurant_menu,
          ),
          const SizedBox(height: 24),

          // Allergies section
          _buildSummarySection(
            title: 'Allergies & Restrictions',
            items: profile.allergies,
            icon: Icons.no_food,
            emptyMessage: 'No allergies or restrictions',
          ),
          const SizedBox(height: 24),

          // Nutrition goals section
          _buildSummaryItem(
            title: 'Nutrition Goal',
            value: profile.nutritionalGoal,
            icon: Icons.track_changes,
          ),
          const SizedBox(height: 16),
          _buildSummaryItem(
            title: 'Daily Calories',
            value: '${profile.targetCalories} calories',
            icon: Icons.local_fire_department,
          ),
          const SizedBox(height: 16),
          _buildSummaryItem(
            title: 'Household Size',
            value: profile.householdSize == 1
                ? '1 person'
                : '${profile.householdSize} people',
            icon: Icons.people,
          ),
          const SizedBox(height: 24),

          // Macros section
          _buildMacrosSection(profile.targetMacros),

          const Spacer(),

          // Complete button
          OnboardingButton(
            label: 'Get Started with Foodster',
            onPressed: onComplete,
            icon: Icons.check_circle,
          ),
        ],
      ),
    );
  }

  Widget _buildSummarySection({
    required String title,
    required List<String> items,
    required IconData icon,
    String emptyMessage = 'None selected',
  }) {
    return Column(
      crossAxisAlignment: CrossAxisAlignment.start,
      children: [
        Row(
          children: [
            Icon(icon, color: AppTheme.primaryOrange),
            const SizedBox(width: 8),
            Text(
              title,
              style: TextStyle(
                color: AppTheme.white,
                fontSize: 18,
                fontWeight: FontWeight.w600,
              ),
            ),
          ],
        ),
        const SizedBox(height: 12),
        if (items.isEmpty)
          Padding(
            padding: const EdgeInsets.only(left: 32),
            child: Text(
              emptyMessage,
              style: TextStyle(
                color: AppTheme.mediumGray,
                fontSize: 16,
                fontStyle: FontStyle.italic,
              ),
            ),
          )
        else
          Padding(
            padding: const EdgeInsets.only(left: 32),
            child: Wrap(
              spacing: 8,
              runSpacing: 8,
              children: items.map((item) {
                return Container(
                  padding: const EdgeInsets.symmetric(
                    horizontal: 12,
                    vertical: 6,
                  ),
                  decoration: BoxDecoration(
                    color: AppTheme.slate,
                    borderRadius: BorderRadius.circular(16),
                  ),
                  child: Text(
                    item,
                    style: TextStyle(color: AppTheme.white, fontSize: 14),
                  ),
                );
              }).toList(),
            ),
          ),
      ],
    );
  }

  Widget _buildSummaryItem({
    required String title,
    required String value,
    required IconData icon,
  }) {
    return Row(
      children: [
        Icon(icon, color: AppTheme.primaryOrange, size: 20),
        const SizedBox(width: 12),
        Text(
          '$title:',
          style: TextStyle(color: AppTheme.mediumGray, fontSize: 16),
        ),
        const SizedBox(width: 8),
        Text(
          value,
          style: TextStyle(
            color: AppTheme.white,
            fontSize: 16,
            fontWeight: FontWeight.w600,
          ),
        ),
      ],
    );
  }

  Widget _buildMacrosSection(Map<String, int> macros) {
    final protein = macros['protein'] ?? 0;
    final carbs = macros['carbs'] ?? 0;
    final fat = macros['fat'] ?? 0;

    return Column(
      crossAxisAlignment: CrossAxisAlignment.start,
      children: [
        Row(
          children: [
            Icon(Icons.pie_chart, color: AppTheme.primaryOrange),
            const SizedBox(width: 8),
            Text(
              'Daily Macronutrients',
              style: TextStyle(
                color: AppTheme.white,
                fontSize: 18,
                fontWeight: FontWeight.w600,
              ),
            ),
          ],
        ),
        const SizedBox(height: 16),
        Row(
          children: [
            _buildMacroCard(
              label: 'Protein',
              value: '$protein g',
              color: AppTheme.accentPurple,
            ),
            const SizedBox(width: 12),
            _buildMacroCard(
              label: 'Carbs',
              value: '$carbs g',
              color: AppTheme.primaryCoral,
            ),
            const SizedBox(width: 12),
            _buildMacroCard(
              label: 'Fat',
              value: '$fat g',
              color: AppTheme.primaryPeach,
            ),
          ],
        ),
      ],
    );
  }

  Widget _buildMacroCard({
    required String label,
    required String value,
    required Color color,
  }) {
    return Expanded(
      child: Container(
        padding: const EdgeInsets.symmetric(vertical: 12),
        decoration: BoxDecoration(
          color: color.withOpacity(0.2),
          borderRadius: BorderRadius.circular(12),
        ),
        child: Column(
          children: [
            Text(
              label,
              style: TextStyle(color: color, fontWeight: FontWeight.w600),
            ),
            const SizedBox(height: 4),
            Text(
              value,
              style: TextStyle(
                color: AppTheme.white,
                fontWeight: FontWeight.bold,
                fontSize: 18,
              ),
            ),
          ],
        ),
      ),
    );
  }
}

</file>
<file path="lib/features/onboarding/presentation/pages/welcome_page.dart">
import 'package:flutter/material.dart';
import '../../../../core/theme/app_theme.dart';
import '../widgets/onboarding_button.dart';

/// First page of onboarding with welcome message
class WelcomePage extends StatelessWidget {
  final VoidCallback onNext;

  const WelcomePage({super.key, required this.onNext});

  @override
  Widget build(BuildContext context) {
    return Padding(
      padding: const EdgeInsets.all(24),
      child: Column(
        crossAxisAlignment: CrossAxisAlignment.start,
        children: [
          const Spacer(flex: 1),
          // Logo and welcome elements
          Center(
            child: Container(
              padding: const EdgeInsets.all(20),
              decoration: BoxDecoration(
                color: AppTheme.primaryOrange.withOpacity(0.1),
                shape: BoxShape.circle,
              ),
              child: const Icon(
                Icons.restaurant_rounded,
                color: AppTheme.primaryOrange,
                size: 60,
              ),
            ),
          ),
          const SizedBox(height: 32),

          // Welcome text
          Text(
            'Welcome to Foodster',
            style: TextStyle(
              color: AppTheme.white,
              fontSize: 28,
              fontWeight: FontWeight.bold,
            ),
          ),
          const SizedBox(height: 16),
          Text(
            'Let\'s set up your personal nutrition profile to create the perfect meal plans and grocery lists for you.',
            style: TextStyle(
              color: AppTheme.mediumGray,
              fontSize: 16,
              height: 1.5,
            ),
          ),
          const SizedBox(height: 24),

          // Key features bullets
          _buildFeatureBullet(
            icon: Icons.restaurant_menu,
            title: 'Personalized meal plans',
            description: 'Based on your dietary needs and preferences',
          ),
          const SizedBox(height: 16),
          _buildFeatureBullet(
            icon: Icons.shopping_cart,
            title: 'Smart grocery lists',
            description: 'With budget optimization and store comparison',
          ),
          const SizedBox(height: 16),
          _buildFeatureBullet(
            icon: Icons.pie_chart,
            title: 'Nutritional insights',
            description: 'Track and optimize your dietary goals',
          ),

          const Spacer(flex: 2),

          // Get started button
          OnboardingButton(
            label: 'Get Started',
            onPressed: onNext,
            icon: Icons.arrow_forward,
          ),
        ],
      ),
    );
  }

  Widget _buildFeatureBullet({
    required IconData icon,
    required String title,
    required String description,
  }) {
    return Row(
      crossAxisAlignment: CrossAxisAlignment.start,
      children: [
        Container(
          padding: const EdgeInsets.all(8),
          decoration: BoxDecoration(
            color: AppTheme.slate,
            borderRadius: BorderRadius.circular(8),
          ),
          child: Icon(icon, size: 20, color: AppTheme.primaryOrange),
        ),
        const SizedBox(width: 16),
        Expanded(
          child: Column(
            crossAxisAlignment: CrossAxisAlignment.start,
            children: [
              Text(
                title,
                style: TextStyle(
                  color: AppTheme.white,
                  fontSize: 16,
                  fontWeight: FontWeight.w600,
                ),
              ),
              const SizedBox(height: 4),
              Text(
                description,
                style: TextStyle(color: AppTheme.mediumGray, fontSize: 14),
              ),
            ],
          ),
        ),
      ],
    );
  }
}

</file>
<file path="lib/features/onboarding/presentation/widgets/onboarding_button.dart">
import 'package:flutter/material.dart';
import '../../../../core/theme/app_theme.dart';

/// Standard navigation button for onboarding
class OnboardingButton extends StatelessWidget {
  final String label;
  final VoidCallback onPressed;
  final bool isPrimary;
  final bool isEnabled;
  final IconData? icon;

  const OnboardingButton({
    super.key,
    required this.label,
    required this.onPressed,
    this.isPrimary = true,
    this.isEnabled = true,
    this.icon,
  });

  @override
  Widget build(BuildContext context) {
    return ElevatedButton(
      onPressed: isEnabled ? onPressed : null,
      style: ElevatedButton.styleFrom(
        backgroundColor: isPrimary
            ? AppTheme.primaryOrange
            : Colors.transparent,
        foregroundColor: isPrimary ? AppTheme.darkNavy : AppTheme.white,
        disabledBackgroundColor: isPrimary
            ? AppTheme.primaryOrange.withOpacity(0.3)
            : Colors.transparent,
        disabledForegroundColor: isPrimary
            ? AppTheme.darkNavy.withOpacity(0.6)
            : AppTheme.mediumGray,
        padding: const EdgeInsets.symmetric(vertical: 14, horizontal: 24),
        shape: RoundedRectangleBorder(
          borderRadius: BorderRadius.circular(16),
          side: isPrimary
              ? BorderSide.none
              : BorderSide(
                  color: isEnabled ? AppTheme.mediumGray : AppTheme.slate,
                ),
        ),
        elevation: isPrimary ? 2 : 0,
        minimumSize: const Size(double.infinity, 56),
      ),
      child: Row(
        mainAxisAlignment: MainAxisAlignment.center,
        children: [
          if (icon != null) ...[Icon(icon, size: 20), const SizedBox(width: 8)],
          Text(
            label,
            style: const TextStyle(fontSize: 16, fontWeight: FontWeight.bold),
          ),
        ],
      ),
    );
  }
}

/// Back button for onboarding
class OnboardingBackButton extends StatelessWidget {
  final VoidCallback onPressed;

  const OnboardingBackButton({super.key, required this.onPressed});

  @override
  Widget build(BuildContext context) {
    return IconButton(
      icon: const Icon(Icons.arrow_back_ios_new, color: AppTheme.white),
      onPressed: onPressed,
    );
  }
}

</file>
<file path="lib/features/onboarding/presentation/widgets/onboarding_progress.dart">
import 'package:flutter/material.dart';
import '../../../../core/theme/app_theme.dart';

/// Progress indicator for onboarding steps
class OnboardingProgress extends StatelessWidget {
  final int currentStep;
  final int totalSteps;

  const OnboardingProgress({
    super.key,
    required this.currentStep,
    required this.totalSteps,
  });

  @override
  Widget build(BuildContext context) {
    return Row(
      children: List.generate(
        totalSteps,
        (index) => Expanded(
          child: Container(
            height: 4,
            margin: const EdgeInsets.symmetric(horizontal: 2),
            decoration: BoxDecoration(
              color: index <= currentStep
                  ? AppTheme.primaryOrange
                  : AppTheme.slate,
              borderRadius: BorderRadius.circular(2),
            ),
          ),
        ),
      ),
    );
  }
}

</file>
<file path="lib/features/onboarding/presentation/widgets/selection_card.dart">
import 'package:flutter/material.dart';
import '../../../../core/theme/app_theme.dart';

/// Selectable card for options in onboarding
class SelectionCard extends StatelessWidget {
  final String title;
  final IconData icon;
  final bool isSelected;
  final VoidCallback onTap;
  final String? description;

  const SelectionCard({
    super.key,
    required this.title,
    required this.icon,
    required this.isSelected,
    required this.onTap,
    this.description,
  });

  @override
  Widget build(BuildContext context) {
    return GestureDetector(
      onTap: onTap,
      child: Container(
        width: double.infinity,
        margin: const EdgeInsets.symmetric(vertical: 8),
        padding: const EdgeInsets.all(16),
        decoration: BoxDecoration(
          color: isSelected
              ? AppTheme.primaryOrange.withOpacity(0.1)
              : AppTheme.slate,
          borderRadius: BorderRadius.circular(16),
          border: Border.all(
            color: isSelected ? AppTheme.primaryOrange : AppTheme.slate,
            width: 2,
          ),
        ),
        child: Row(
          children: [
            Container(
              width: 50,
              height: 50,
              decoration: BoxDecoration(
                color: isSelected ? AppTheme.primaryOrange : AppTheme.charcoal,
                borderRadius: BorderRadius.circular(12),
              ),
              child: Icon(
                icon,
                color: isSelected ? AppTheme.darkNavy : AppTheme.mediumGray,
                size: 28,
              ),
            ),
            const SizedBox(width: 16),
            Expanded(
              child: Column(
                crossAxisAlignment: CrossAxisAlignment.start,
                children: [
                  Text(
                    title,
                    style: TextStyle(
                      fontSize: 18,
                      fontWeight: FontWeight.bold,
                      color: isSelected
                          ? AppTheme.primaryOrange
                          : AppTheme.white,
                    ),
                  ),
                  if (description != null) ...[
                    const SizedBox(height: 4),
                    Text(
                      description!,
                      style: TextStyle(
                        fontSize: 14,
                        color: AppTheme.mediumGray,
                      ),
                    ),
                  ],
                ],
              ),
            ),
            if (isSelected)
              Icon(Icons.check_circle, color: AppTheme.primaryOrange, size: 24),
          ],
        ),
      ),
    );
  }
}

/// Smaller selection chip for multi-select options
class SelectionChip extends StatelessWidget {
  final String label;
  final bool isSelected;
  final VoidCallback onTap;

  const SelectionChip({
    super.key,
    required this.label,
    required this.isSelected,
    required this.onTap,
  });

  @override
  Widget build(BuildContext context) {
    return GestureDetector(
      onTap: onTap,
      child: Container(
        margin: const EdgeInsets.only(right: 8, bottom: 8),
        padding: const EdgeInsets.symmetric(horizontal: 16, vertical: 8),
        decoration: BoxDecoration(
          color: isSelected ? AppTheme.primaryOrange : AppTheme.slate,
          borderRadius: BorderRadius.circular(20),
        ),
        child: Text(
          label,
          style: TextStyle(
            color: isSelected ? AppTheme.darkNavy : AppTheme.white,
            fontWeight: isSelected ? FontWeight.bold : FontWeight.normal,
          ),
        ),
      ),
    );
  }
}

</file>
<file path="lib/features/onboarding/presentation/widgets/selection_chip.dart">
import 'package:flutter/material.dart';
import '../../../../core/theme/app_theme.dart';

/// Small chip for selection in onboarding pages
class SelectionChip extends StatelessWidget {
  final String label;
  final bool isSelected;
  final VoidCallback onTap;

  const SelectionChip({
    super.key,
    required this.label,
    required this.isSelected,
    required this.onTap,
  });

  @override
  Widget build(BuildContext context) {
    return GestureDetector(
      onTap: onTap,
      child: Container(
        margin: const EdgeInsets.only(right: 8, bottom: 8),
        padding: const EdgeInsets.symmetric(horizontal: 16, vertical: 8),
        decoration: BoxDecoration(
          color: isSelected ? AppTheme.primaryOrange : AppTheme.slate,
          borderRadius: BorderRadius.circular(20),
        ),
        child: Text(
          label,
          style: TextStyle(
            color: isSelected ? AppTheme.darkNavy : AppTheme.white,
            fontWeight: isSelected ? FontWeight.bold : FontWeight.normal,
          ),
        ),
      ),
    );
  }
}

</file>
<file path="lib/features/profile/presentation/pages/profile_page.dart">
import 'package:flutter/material.dart';
import '../../../../core/theme/app_theme.dart';
import '../../../../core/network/supabase_service.dart';
import '../../../../core/routes/app_router.dart';
import '../widgets/auth_section.dart';

/// Profile page for user settings
class ProfilePage extends StatelessWidget {
  const ProfilePage({super.key});

  @override
  Widget build(BuildContext context) {
    // Mock user data - in real app would come from a user repository
    final user = SupabaseService.currentUser;
    final userData = {
      'name': 'Alex Johnson',
      'email': user?.email ?? 'alex@example.com',
      'photoUrl': null,
      'dietaryPreferences': ['Vegetarian', 'Low Carb'],
      'allergies': ['Nuts', 'Shellfish'],
      'nutritionGoals': {
        'calories': 2200,
        'protein': 120,
        'carbs': 220,
        'fat': 70,
      },
    };

    return Scaffold(
      backgroundColor: AppTheme.darkNavy,
      appBar: AppBar(
        title: const Text('Profile'),
        actions: [
          IconButton(
            icon: const Icon(Icons.settings),
            onPressed: () {
              // TODO: Navigate to settings
            },
          ),
        ],
      ),
      body: SingleChildScrollView(
        child: Padding(
          padding: const EdgeInsets.all(16.0),
          child: Column(
            crossAxisAlignment: CrossAxisAlignment.start,
            children: [
              _buildProfileHeader(userData),
              const SizedBox(height: 24),

              // Authentication section
              const AuthSection(),
              const SizedBox(height: 24),

              _buildSectionTitle('Dietary Preferences'),
              const SizedBox(height: 8),
              _buildChipList(userData['dietaryPreferences'] as List<String>),

              const SizedBox(height: 24),
              _buildSectionTitle('Allergies'),
              const SizedBox(height: 8),
              _buildChipList(userData['allergies'] as List<String>),

              const SizedBox(height: 24),
              _buildSectionTitle('Nutrition Goals'),
              const SizedBox(height: 16),
              _buildNutritionGoals(
                userData['nutritionGoals'] as Map<String, dynamic>,
              ),

              const SizedBox(height: 24),
              _buildSectionTitle('Account'),
              const SizedBox(height: 16),
              _buildAccountSettings(context),
            ],
          ),
        ),
      ),
    );
  }

  Widget _buildProfileHeader(Map<String, dynamic> userData) {
    return Center(
      child: Column(
        children: [
          Container(
            height: 100,
            width: 100,
            decoration: BoxDecoration(
              color: AppTheme.slate,
              shape: BoxShape.circle,
              border: Border.all(color: AppTheme.primaryOrange, width: 2),
            ),
            child: userData['photoUrl'] != null
                ? ClipRRect(
                    borderRadius: BorderRadius.circular(50),
                    child: Image.network(
                      userData['photoUrl'] as String,
                      fit: BoxFit.cover,
                    ),
                  )
                : const Center(
                    child: Icon(Icons.person, size: 50, color: AppTheme.white),
                  ),
          ),
          const SizedBox(height: 16),
          Text(
            userData['name'] as String,
            style: TextStyle(
              color: AppTheme.white,
              fontSize: 24,
              fontWeight: FontWeight.w600,
            ),
          ),
          const SizedBox(height: 4),
          Text(
            userData['email'] as String,
            style: TextStyle(color: AppTheme.mediumGray, fontSize: 16),
          ),
          const SizedBox(height: 16),
          ElevatedButton(
            style: ElevatedButton.styleFrom(
              backgroundColor: AppTheme.charcoal,
              foregroundColor: AppTheme.primaryOrange,
              padding: const EdgeInsets.symmetric(horizontal: 24, vertical: 12),
              shape: RoundedRectangleBorder(
                borderRadius: BorderRadius.circular(12),
              ),
            ),
            onPressed: () {
              // TODO: Navigate to edit profile
            },
            child: const Text('Edit Profile'),
          ),
        ],
      ),
    );
  }

  Widget _buildSectionTitle(String title) {
    return Text(
      title,
      style: TextStyle(
        color: AppTheme.white,
        fontSize: 18,
        fontWeight: FontWeight.w600,
      ),
    );
  }

  Widget _buildChipList(List<String> items) {
    return Wrap(
      spacing: 8,
      runSpacing: 8,
      children: items.map((item) => _buildChip(item)).toList(),
    );
  }

  Widget _buildChip(String label) {
    return Chip(
      label: Text(label),
      backgroundColor: AppTheme.charcoal,
      labelStyle: TextStyle(color: AppTheme.white),
      padding: const EdgeInsets.symmetric(horizontal: 8, vertical: 0),
      shape: RoundedRectangleBorder(
        borderRadius: BorderRadius.circular(16),
        side: BorderSide(color: AppTheme.primaryOrange.withOpacity(0.3)),
      ),
    );
  }

  Widget _buildNutritionGoals(Map<String, dynamic> goals) {
    return Container(
      padding: const EdgeInsets.all(16),
      decoration: BoxDecoration(
        color: AppTheme.charcoal,
        borderRadius: BorderRadius.circular(16),
      ),
      child: Row(
        mainAxisAlignment: MainAxisAlignment.spaceAround,
        children: [
          _buildNutrientGoal(
            label: 'Calories',
            value: '${goals['calories']}',
            color: AppTheme.primaryOrange,
          ),
          _buildNutrientGoal(
            label: 'Protein',
            value: '${goals['protein']}g',
            color: AppTheme.accentYellow,
          ),
          _buildNutrientGoal(
            label: 'Carbs',
            value: '${goals['carbs']}g',
            color: AppTheme.primaryCoral,
          ),
          _buildNutrientGoal(
            label: 'Fat',
            value: '${goals['fat']}g',
            color: AppTheme.accentPurple,
          ),
        ],
      ),
    );
  }

  Widget _buildNutrientGoal({
    required String label,
    required String value,
    required Color color,
  }) {
    return Column(
      children: [
        Text(
          value,
          style: TextStyle(
            color: color,
            fontWeight: FontWeight.w700,
            fontSize: 18,
          ),
        ),
        const SizedBox(height: 4),
        Text(label, style: TextStyle(color: AppTheme.mediumGray, fontSize: 12)),
      ],
    );
  }

  Widget _buildAccountSettings(BuildContext context) {
    final settingsItems = [
      {
        'title': 'Notification Settings',
        'icon': Icons.notifications_outlined,
        'onTap': () {
          // TODO: Navigate to notification settings
        },
      },
      {
        'title': 'Privacy',
        'icon': Icons.lock_outline,
        'onTap': () {
          // TODO: Navigate to privacy settings
        },
      },
      {
        'title': 'Payment Methods',
        'icon': Icons.payment_outlined,
        'onTap': () {
          // TODO: Navigate to payment methods
        },
      },
      {
        'title': 'Help & Support',
        'icon': Icons.help_outline,
        'onTap': () {
          // TODO: Navigate to help & support
        },
      },
      {
        'title': 'About',
        'icon': Icons.info_outline,
        'onTap': () {
          // TODO: Navigate to about page
        },
      },
      {
        'title': 'Log Out',
        'icon': Icons.logout,
        'onTap': () async {
          // await SupabaseService.signOut();
          if (context.mounted) {
            // Temporarily navigate to onboarding for testing
            Navigator.of(context).pushReplacementNamed(AppRouter.onboarding);
          }
        },
      },
    ];

    return Container(
      decoration: BoxDecoration(
        color: AppTheme.charcoal,
        borderRadius: BorderRadius.circular(16),
      ),
      child: ListView.separated(
        physics: const NeverScrollableScrollPhysics(),
        shrinkWrap: true,
        itemCount: settingsItems.length,
        separatorBuilder: (context, index) => const Divider(
          color: AppTheme.slate,
          height: 1,
          indent: 16,
          endIndent: 16,
        ),
        itemBuilder: (context, index) {
          final item = settingsItems[index];
          return ListTile(
            leading: Icon(item['icon'] as IconData, color: AppTheme.mediumGray),
            title: Text(
              item['title'] as String,
              style: TextStyle(
                color: AppTheme.white,
                fontWeight: index == settingsItems.length - 1
                    ? FontWeight.w600
                    : null,
              ),
            ),
            trailing: const Icon(
              Icons.chevron_right,
              color: AppTheme.mediumGray,
            ),
            contentPadding: const EdgeInsets.symmetric(
              horizontal: 16,
              vertical: 4,
            ),
            onTap: item['onTap'] as Function(),
          );
        },
      ),
    );
  }
}

</file>
<file path="lib/features/profile/presentation/widgets/auth_section.dart">
import 'package:flutter/material.dart';
import '../../../../core/theme/app_theme.dart';
import '../../../../core/routes/app_router.dart';
import '../../../../core/network/supabase_service.dart';
import '../pages/profile_page.dart';

/// Authentication section for Profile page
class AuthSection extends StatelessWidget {
  const AuthSection({super.key});

  @override
  Widget build(BuildContext context) {
    final isLoggedIn = SupabaseService.currentUser != null;

    return Container(
      padding: const EdgeInsets.all(20),
      decoration: BoxDecoration(
        color: AppTheme.slate,
        borderRadius: BorderRadius.circular(16),
        boxShadow: [
          BoxShadow(
            color: Colors.black.withOpacity(0.1),
            blurRadius: 8,
            offset: const Offset(0, 4),
          ),
        ],
      ),
      child: Column(
        crossAxisAlignment: CrossAxisAlignment.start,
        children: [
          Row(
            children: [
              Icon(
                isLoggedIn ? Icons.check_circle : Icons.account_circle,
                color: isLoggedIn
                    ? AppTheme.successGreen
                    : AppTheme.primaryOrange,
                size: 24,
              ),
              const SizedBox(width: 12),
              Text(
                'Account',
                style: AppTheme.h3.copyWith(color: AppTheme.white),
              ),
            ],
          ),
          const SizedBox(height: 16),

          if (isLoggedIn) ...[
            // Logged in state
            Text(
              'You\'re signed in as:',
              style: TextStyle(color: AppTheme.mediumGray, fontSize: 14),
            ),
            const SizedBox(height: 8),
            Text(
              SupabaseService.currentUser?.email ?? '',
              style: TextStyle(
                color: AppTheme.white,
                fontSize: 16,
                fontWeight: FontWeight.bold,
              ),
            ),
            const SizedBox(height: 20),
            ElevatedButton.icon(
              onPressed: () async {
                await SupabaseService.signOut();
                // Show confirmation
                if (!context.mounted) return;
                ScaffoldMessenger.of(context).showSnackBar(
                  const SnackBar(
                    content: Text('You\'ve been signed out.'),
                    backgroundColor: AppTheme.slate,
                  ),
                );
                // Refresh the page
                if (!context.mounted) return;
                Navigator.of(context).pushReplacement(
                  MaterialPageRoute(builder: (_) => const ProfilePage()),
                );
              },
              icon: const Icon(Icons.logout),
              label: const Text('Sign Out'),
              style: ElevatedButton.styleFrom(
                backgroundColor: AppTheme.slate,
                foregroundColor: AppTheme.primaryOrange,
                elevation: 0,
                side: BorderSide(
                  color: AppTheme.primaryOrange.withOpacity(0.5),
                ),
              ),
            ),
          ] else ...[
            // Logged out state
            Text(
              'Create an account or sign in to:',
              style: TextStyle(color: AppTheme.white, fontSize: 16),
            ),
            const SizedBox(height: 12),
            _buildBenefitRow(
              Icons.cloud_upload,
              'Sync your data across devices',
            ),
            const SizedBox(height: 8),
            _buildBenefitRow(Icons.favorite, 'Save your favorite recipes'),
            const SizedBox(height: 8),
            _buildBenefitRow(Icons.share, 'Share meal plans with family'),
            const SizedBox(height: 20),
            Row(
              children: [
                Expanded(
                  child: ElevatedButton(
                    onPressed: () =>
                        Navigator.of(context).pushNamed(AppRouter.login),
                    style: ElevatedButton.styleFrom(
                      backgroundColor: AppTheme.primaryOrange,
                      foregroundColor: AppTheme.darkNavy,
                      padding: const EdgeInsets.symmetric(vertical: 12),
                    ),
                    child: const Text('Sign In'),
                  ),
                ),
                const SizedBox(width: 12),
                Expanded(
                  child: ElevatedButton(
                    onPressed: () =>
                        Navigator.of(context).pushNamed(AppRouter.signup),
                    style: ElevatedButton.styleFrom(
                      backgroundColor: AppTheme.slate,
                      foregroundColor: AppTheme.primaryOrange,
                      padding: const EdgeInsets.symmetric(vertical: 12),
                      elevation: 0,
                      side: BorderSide(
                        color: AppTheme.primaryOrange.withOpacity(0.5),
                      ),
                    ),
                    child: const Text('Sign Up'),
                  ),
                ),
              ],
            ),
          ],
        ],
      ),
    );
  }

  /// Helper to build a row with icon and text
  Widget _buildBenefitRow(IconData icon, String text) {
    return Row(
      children: [
        Icon(icon, color: AppTheme.primaryOrange, size: 18),
        const SizedBox(width: 8),
        Expanded(
          child: Text(
            text,
            style: TextStyle(color: AppTheme.mediumGray, fontSize: 14),
          ),
        ),
      ],
    );
  }
}

</file>
<file path="lib/features/recipes/data/models/recipe_model.dart">
import '../../../../features/nutrition/data/models/nutrition_model.dart';
import '../../domain/entities/recipe.dart';

/// Data model for Recipe
class RecipeModel extends Recipe {
  const RecipeModel({
    required super.id,
    required super.name,
    super.description,
    super.imageUrl,
    required super.prepTimeMinutes,
    required super.cookTimeMinutes,
    required super.servings,
    required super.ingredients,
    required super.instructions,
    required super.nutrition,
    super.mealType,
    super.tags,
    super.rating,
    super.isFavorite,
  });

  factory RecipeModel.fromJson(Map<String, dynamic> json) {
    return RecipeModel(
      id: json['id'],
      name: json['name'],
      description: json['description'],
      imageUrl: json['imageUrl'],
      prepTimeMinutes: json['prepTimeMinutes'],
      cookTimeMinutes: json['cookTimeMinutes'],
      servings: json['servings'],
      ingredients: List<String>.from(json['ingredients']),
      instructions: List<String>.from(json['instructions']),
      nutrition: NutritionModel.fromJson(json['nutrition']),
      mealType: json['mealType'],
      tags: json['tags'] != null ? List<String>.from(json['tags']) : null,
      rating: json['rating']?.toDouble(),
      isFavorite: json['isFavorite'] ?? false,
    );
  }

  Map<String, dynamic> toJson() {
    return {
      'id': id,
      'name': name,
      'description': description,
      'imageUrl': imageUrl,
      'prepTimeMinutes': prepTimeMinutes,
      'cookTimeMinutes': cookTimeMinutes,
      'servings': servings,
      'ingredients': ingredients,
      'instructions': instructions,
      'nutrition': nutrition.toJson(),
      'mealType': mealType,
      'tags': tags,
      'rating': rating,
      'isFavorite': isFavorite,
    };
  }

  /// Get mock recipes for testing
  static List<Recipe> getMockRecipes() {
    return [
      RecipeModel(
        id: '1',
        name: 'Quinoa Buddha Bowl',
        description:
            'A nutritious bowl packed with protein and fresh vegetables',
        imageUrl:
            'https://images.pexels.com/photos/248509/pexels-photo-248509.jpeg',
        prepTimeMinutes: 15,
        cookTimeMinutes: 20,
        servings: 1,
        ingredients: [
          '1 cup quinoa',
          '1 cup mixed vegetables',
          '1 avocado',
          'tahini dressing',
        ],
        instructions: [
          'Cook quinoa',
          'Roast vegetables',
          'Arrange in bowl',
          'Top with dressing',
        ],
        nutrition: NutritionModel(
          calories: 450,
          protein: NutrientModel(
            value: 15,
            unit: 'g',
            label: 'Protein',
            dailyValue: 30,
          ),
          fat: NutrientModel(
            value: 22,
            unit: 'g',
            label: 'Fat',
            dailyValue: 28,
          ),
          carbs: NutrientModel(
            value: 52,
            unit: 'g',
            label: 'Carbs',
            dailyValue: 17,
          ),
          fiber: NutrientModel(
            value: 8,
            unit: 'g',
            label: 'Fiber',
            dailyValue: 32,
          ),
          sugar: NutrientModel(value: 4, unit: 'g', label: 'Sugar'),
          sodium: NutrientModel(
            value: 380,
            unit: 'mg',
            label: 'Sodium',
            dailyValue: 16,
          ),
          cholesterol: NutrientModel(
            value: 0,
            unit: 'mg',
            label: 'Cholesterol',
            dailyValue: 0,
          ),
          fatBreakdown: FatBreakdownModel(
            saturated: NutrientModel(
              value: 4.2,
              unit: 'g',
              label: 'Saturated Fat',
              dailyValue: 21,
            ),
            polyunsaturated: NutrientModel(
              value: 6.8,
              unit: 'g',
              label: 'Polyunsaturated Fat',
            ),
            monounsaturated: NutrientModel(
              value: 9.4,
              unit: 'g',
              label: 'Monounsaturated Fat',
            ),
            trans: NutrientModel(value: 0, unit: 'g', label: 'Trans Fat'),
            omega3: NutrientModel(value: 2.2, unit: 'g', label: 'Omega 3'),
            omega6: NutrientModel(value: 4.6, unit: 'g', label: 'Omega 6'),
          ),
          vitamins: VitaminModel(
            a: NutrientModel(
              value: 800,
              unit: 'IU',
              label: 'Vitamin A',
              dailyValue: 16,
            ),
            c: NutrientModel(
              value: 14,
              unit: 'mg',
              label: 'Vitamin C',
              dailyValue: 15,
            ),
            d: NutrientModel(
              value: 2,
              unit: 'mcg',
              label: 'Vitamin D',
              dailyValue: 10,
            ),
            e: NutrientModel(
              value: 1.2,
              unit: 'mg',
              label: 'Vitamin E',
              dailyValue: 8,
            ),
            k: NutrientModel(
              value: 15,
              unit: 'mcg',
              label: 'Vitamin K',
              dailyValue: 12,
            ),
            b1: NutrientModel(
              value: 0.3,
              unit: 'mg',
              label: 'Thiamin',
              dailyValue: 25,
            ),
            b2: NutrientModel(
              value: 0.4,
              unit: 'mg',
              label: 'Riboflavin',
              dailyValue: 30,
            ),
            b3: NutrientModel(
              value: 4.2,
              unit: 'mg',
              label: 'Niacin',
              dailyValue: 26,
            ),
            b6: NutrientModel(
              value: 0.5,
              unit: 'mg',
              label: 'Vitamin B6',
              dailyValue: 29,
            ),
            b12: NutrientModel(
              value: 0.8,
              unit: 'mcg',
              label: 'Vitamin B12',
              dailyValue: 33,
            ),
            folate: NutrientModel(
              value: 120,
              unit: 'mcg',
              label: 'Folate',
              dailyValue: 30,
            ),
          ),
          minerals: MineralModel(
            calcium: NutrientModel(
              value: 120,
              unit: 'mg',
              label: 'Calcium',
              dailyValue: 12,
            ),
            iron: NutrientModel(
              value: 2.7,
              unit: 'mg',
              label: 'Iron',
              dailyValue: 15,
            ),
            magnesium: NutrientModel(
              value: 45,
              unit: 'mg',
              label: 'Magnesium',
              dailyValue: 11,
            ),
            phosphorus: NutrientModel(
              value: 210,
              unit: 'mg',
              label: 'Phosphorus',
              dailyValue: 17,
            ),
            potassium: NutrientModel(
              value: 320,
              unit: 'mg',
              label: 'Potassium',
              dailyValue: 8,
            ),
            zinc: NutrientModel(
              value: 1.8,
              unit: 'mg',
              label: 'Zinc',
              dailyValue: 16,
            ),
            selenium: NutrientModel(
              value: 15,
              unit: 'mcg',
              label: 'Selenium',
              dailyValue: 27,
            ),
          ),
          dietLabels: ['Low Fat', 'High Fiber'],
          healthLabels: ['Good Source of Protein', 'Vegetarian'],
          cautions: [],
          servingWeight: 350,
          servings: 1,
        ),
        mealType: 'lunch',
        tags: ['healthy', 'vegetarian', 'bowl'],
        rating: 4.8,
        isFavorite: false,
      ),
      RecipeModel(
        id: '2',
        name: 'Greek Yogurt Parfait',
        description:
            'A protein-rich breakfast parfait with fresh berries and honey',
        imageUrl:
            'https://images.unsplash.com/photo-1488477181946-6428a0291777?ixlib=rb-4.0.3&ixid=M3wxMjA3fDB8MHxwaG90by1wYWdlfHx8fGVufDB8fHx8fA%3D%3D&auto=format&fit=crop&w=2340&q=80',
        prepTimeMinutes: 10,
        cookTimeMinutes: 0,
        servings: 1,
        ingredients: [
          '1 cup Greek yogurt',
          '1/2 cup mixed berries',
          '1/4 cup granola',
          '1 tbsp honey',
        ],
        instructions: [
          'Layer yogurt',
          'Add berries',
          'Top with granola',
          'Drizzle honey',
        ],
        nutrition: NutritionModel(
          calories: 320,
          protein: NutrientModel(
            value: 18,
            unit: 'g',
            label: 'Protein',
            dailyValue: 36,
          ),
          fat: NutrientModel(value: 8, unit: 'g', label: 'Fat', dailyValue: 10),
          carbs: NutrientModel(
            value: 45,
            unit: 'g',
            label: 'Carbs',
            dailyValue: 15,
          ),
          fiber: NutrientModel(
            value: 4,
            unit: 'g',
            label: 'Fiber',
            dailyValue: 16,
          ),
          sugar: NutrientModel(value: 28, unit: 'g', label: 'Sugar'),
          sodium: NutrientModel(
            value: 65,
            unit: 'mg',
            label: 'Sodium',
            dailyValue: 3,
          ),
          cholesterol: NutrientModel(
            value: 15,
            unit: 'mg',
            label: 'Cholesterol',
            dailyValue: 5,
          ),
          fatBreakdown: FatBreakdownModel(
            saturated: NutrientModel(
              value: 2.5,
              unit: 'g',
              label: 'Saturated Fat',
              dailyValue: 13,
            ),
            polyunsaturated: NutrientModel(
              value: 1.2,
              unit: 'g',
              label: 'Polyunsaturated Fat',
            ),
            monounsaturated: NutrientModel(
              value: 3.8,
              unit: 'g',
              label: 'Monounsaturated Fat',
            ),
            trans: NutrientModel(value: 0, unit: 'g', label: 'Trans Fat'),
            omega3: NutrientModel(value: 0.4, unit: 'g', label: 'Omega 3'),
            omega6: NutrientModel(value: 0.8, unit: 'g', label: 'Omega 6'),
          ),
          vitamins: VitaminModel(
            a: NutrientModel(
              value: 120,
              unit: 'IU',
              label: 'Vitamin A',
              dailyValue: 2,
            ),
            c: NutrientModel(
              value: 25,
              unit: 'mg',
              label: 'Vitamin C',
              dailyValue: 28,
            ),
            d: NutrientModel(
              value: 1.2,
              unit: 'mcg',
              label: 'Vitamin D',
              dailyValue: 6,
            ),
            e: NutrientModel(
              value: 0.8,
              unit: 'mg',
              label: 'Vitamin E',
              dailyValue: 5,
            ),
            k: NutrientModel(
              value: 8,
              unit: 'mcg',
              label: 'Vitamin K',
              dailyValue: 7,
            ),
            b1: NutrientModel(
              value: 0.2,
              unit: 'mg',
              label: 'Thiamin',
              dailyValue: 17,
            ),
            b2: NutrientModel(
              value: 0.3,
              unit: 'mg',
              label: 'Riboflavin',
              dailyValue: 23,
            ),
            b3: NutrientModel(
              value: 2.8,
              unit: 'mg',
              label: 'Niacin',
              dailyValue: 18,
            ),
            b6: NutrientModel(
              value: 0.3,
              unit: 'mg',
              label: 'Vitamin B6',
              dailyValue: 18,
            ),
            b12: NutrientModel(
              value: 1.2,
              unit: 'mcg',
              label: 'Vitamin B12',
              dailyValue: 50,
            ),
            folate: NutrientModel(
              value: 85,
              unit: 'mcg',
              label: 'Folate',
              dailyValue: 21,
            ),
          ),
          minerals: MineralModel(
            calcium: NutrientModel(
              value: 250,
              unit: 'mg',
              label: 'Calcium',
              dailyValue: 25,
            ),
            iron: NutrientModel(
              value: 1.2,
              unit: 'mg',
              label: 'Iron',
              dailyValue: 7,
            ),
            magnesium: NutrientModel(
              value: 32,
              unit: 'mg',
              label: 'Magnesium',
              dailyValue: 8,
            ),
            phosphorus: NutrientModel(
              value: 185,
              unit: 'mg',
              label: 'Phosphorus',
              dailyValue: 15,
            ),
            potassium: NutrientModel(
              value: 280,
              unit: 'mg',
              label: 'Potassium',
              dailyValue: 6,
            ),
            zinc: NutrientModel(
              value: 1.4,
              unit: 'mg',
              label: 'Zinc',
              dailyValue: 13,
            ),
            selenium: NutrientModel(
              value: 12,
              unit: 'mcg',
              label: 'Selenium',
              dailyValue: 22,
            ),
          ),
          dietLabels: ['High-Protein', 'Low-Fat'],
          healthLabels: ['Vegetarian', 'Probiotic'],
          cautions: ['Contains Dairy'],
          servingWeight: 280,
          servings: 1,
        ),
        mealType: 'breakfast',
        tags: ['breakfast', 'healthy', 'quick'],
        rating: 4.6,
        isFavorite: true,
      ),
      RecipeModel(
        id: '3',
        name: 'Grilled Salmon with Asparagus',
        description:
            'Pan-seared salmon fillet with grilled asparagus and lemon butter sauce',
        imageUrl:
            'https://images.unsplash.com/photo-1519708227418-c8fd9a32b7a2?ixlib=rb-4.0.3&ixid=M3wxMjA3fDB8MHxwaG90by1wYWdlfHx8fGVufDB8fHx8fA%3D%3D&auto=format&fit=crop&w=2070&q=80',
        prepTimeMinutes: 10,
        cookTimeMinutes: 20,
        servings: 1,
        ingredients: [
          '6 oz salmon fillet',
          '8 asparagus spears',
          '1 tbsp butter',
          '1 lemon',
        ],
        instructions: [
          'Season salmon',
          'Heat pan until hot',
          'Cook salmon 4-5 minutes each side',
          'Grill asparagus',
          'Make lemon butter sauce',
        ],
        nutrition: NutritionModel(
          calories: 420,
          protein: NutrientModel(
            value: 46,
            unit: 'g',
            label: 'Protein',
            dailyValue: 92,
          ),
          fat: NutrientModel(
            value: 24,
            unit: 'g',
            label: 'Fat',
            dailyValue: 31,
          ),
          carbs: NutrientModel(
            value: 8,
            unit: 'g',
            label: 'Carbs',
            dailyValue: 3,
          ),
          fiber: NutrientModel(
            value: 4,
            unit: 'g',
            label: 'Fiber',
            dailyValue: 16,
          ),
          sugar: NutrientModel(value: 2, unit: 'g', label: 'Sugar'),
          sodium: NutrientModel(
            value: 125,
            unit: 'mg',
            label: 'Sodium',
            dailyValue: 5,
          ),
          cholesterol: NutrientModel(
            value: 95,
            unit: 'mg',
            label: 'Cholesterol',
            dailyValue: 32,
          ),
          fatBreakdown: FatBreakdownModel(
            saturated: NutrientModel(
              value: 5.2,
              unit: 'g',
              label: 'Saturated Fat',
              dailyValue: 26,
            ),
            polyunsaturated: NutrientModel(
              value: 8.4,
              unit: 'g',
              label: 'Polyunsaturated Fat',
            ),
            monounsaturated: NutrientModel(
              value: 7.8,
              unit: 'g',
              label: 'Monounsaturated Fat',
            ),
            trans: NutrientModel(value: 0, unit: 'g', label: 'Trans Fat'),
            omega3: NutrientModel(value: 4.2, unit: 'g', label: 'Omega 3'),
            omega6: NutrientModel(value: 4.2, unit: 'g', label: 'Omega 6'),
          ),
          vitamins: VitaminModel(
            a: NutrientModel(
              value: 850,
              unit: 'IU',
              label: 'Vitamin A',
              dailyValue: 17,
            ),
            c: NutrientModel(
              value: 12,
              unit: 'mg',
              label: 'Vitamin C',
              dailyValue: 13,
            ),
            d: NutrientModel(
              value: 12,
              unit: 'mcg',
              label: 'Vitamin D',
              dailyValue: 60,
            ),
            e: NutrientModel(
              value: 4,
              unit: 'mg',
              label: 'Vitamin E',
              dailyValue: 27,
            ),
            k: NutrientModel(
              value: 45,
              unit: 'mcg',
              label: 'Vitamin K',
              dailyValue: 38,
            ),
            b1: NutrientModel(
              value: 0.4,
              unit: 'mg',
              label: 'Thiamin',
              dailyValue: 33,
            ),
            b2: NutrientModel(
              value: 0.6,
              unit: 'mg',
              label: 'Riboflavin',
              dailyValue: 46,
            ),
            b3: NutrientModel(
              value: 12,
              unit: 'mg',
              label: 'Niacin',
              dailyValue: 75,
            ),
            b6: NutrientModel(
              value: 1.2,
              unit: 'mg',
              label: 'Vitamin B6',
              dailyValue: 71,
            ),
            b12: NutrientModel(
              value: 5,
              unit: 'mcg',
              label: 'Vitamin B12',
              dailyValue: 208,
            ),
            folate: NutrientModel(
              value: 90,
              unit: 'mcg',
              label: 'Folate',
              dailyValue: 23,
            ),
          ),
          minerals: MineralModel(
            calcium: NutrientModel(
              value: 40,
              unit: 'mg',
              label: 'Calcium',
              dailyValue: 4,
            ),
            iron: NutrientModel(
              value: 2,
              unit: 'mg',
              label: 'Iron',
              dailyValue: 11,
            ),
            magnesium: NutrientModel(
              value: 55,
              unit: 'mg',
              label: 'Magnesium',
              dailyValue: 13,
            ),
            phosphorus: NutrientModel(
              value: 450,
              unit: 'mg',
              label: 'Phosphorus',
              dailyValue: 36,
            ),
            potassium: NutrientModel(
              value: 850,
              unit: 'mg',
              label: 'Potassium',
              dailyValue: 18,
            ),
            zinc: NutrientModel(
              value: 1.2,
              unit: 'mg',
              label: 'Zinc',
              dailyValue: 11,
            ),
            selenium: NutrientModel(
              value: 45,
              unit: 'mcg',
              label: 'Selenium',
              dailyValue: 82,
            ),
          ),
          dietLabels: ['High-Protein', 'Low-Carb'],
          healthLabels: ['Pescatarian', 'Gluten-Free'],
          cautions: ['Fish'],
          servingWeight: 280,
          servings: 1,
        ),
        mealType: 'dinner',
        tags: ['seafood', 'healthy', 'keto-friendly'],
        rating: 4.9,
        isFavorite: false,
      ),
      RecipeModel(
        id: '4',
        name: 'Chicken Fajita Bowl',
        description:
            'Spicy grilled chicken with bell peppers, onions, and brown rice',
        imageUrl:
            'https://images.unsplash.com/photo-1666025954339-97a97468de17?q=80&w=2940&auto=format&fit=crop&ixlib=rb-4.1.0&ixid=M3wxMjA3fDB8MHxwaG90by1wYWdlfHx8fGVufDB8fHx8fA%3D%3D',
        prepTimeMinutes: 15,
        cookTimeMinutes: 25,
        servings: 1,
        ingredients: [
          '6 oz chicken breast',
          '1 cup brown rice',
          'mixed bell peppers',
          'onions',
          'fajita seasoning',
        ],
        instructions: [
          'Cook rice',
          'Season chicken',
          'Grill chicken and vegetables',
          'Assemble bowl',
        ],
        nutrition: NutritionModel(
          calories: 520,
          protein: NutrientModel(
            value: 42,
            unit: 'g',
            label: 'Protein',
            dailyValue: 84,
          ),
          fat: NutrientModel(
            value: 18,
            unit: 'g',
            label: 'Fat',
            dailyValue: 23,
          ),
          carbs: NutrientModel(
            value: 56,
            unit: 'g',
            label: 'Carbs',
            dailyValue: 19,
          ),
          fiber: NutrientModel(
            value: 6,
            unit: 'g',
            label: 'Fiber',
            dailyValue: 24,
          ),
          sugar: NutrientModel(value: 4, unit: 'g', label: 'Sugar'),
          sodium: NutrientModel(
            value: 520,
            unit: 'mg',
            label: 'Sodium',
            dailyValue: 22,
          ),
          cholesterol: NutrientModel(
            value: 125,
            unit: 'mg',
            label: 'Cholesterol',
            dailyValue: 42,
          ),
          fatBreakdown: FatBreakdownModel(
            saturated: NutrientModel(
              value: 4.8,
              unit: 'g',
              label: 'Saturated Fat',
              dailyValue: 24,
            ),
            polyunsaturated: NutrientModel(
              value: 4.2,
              unit: 'g',
              label: 'Polyunsaturated Fat',
            ),
            monounsaturated: NutrientModel(
              value: 7.4,
              unit: 'g',
              label: 'Monounsaturated Fat',
            ),
            trans: NutrientModel(value: 0.2, unit: 'g', label: 'Trans Fat'),
            omega3: NutrientModel(value: 1.2, unit: 'g', label: 'Omega 3'),
            omega6: NutrientModel(value: 3.0, unit: 'g', label: 'Omega 6'),
          ),
          vitamins: VitaminModel(
            a: NutrientModel(
              value: 1200,
              unit: 'IU',
              label: 'Vitamin A',
              dailyValue: 24,
            ),
            c: NutrientModel(
              value: 85,
              unit: 'mg',
              label: 'Vitamin C',
              dailyValue: 94,
            ),
            d: NutrientModel(
              value: 0,
              unit: 'mcg',
              label: 'Vitamin D',
              dailyValue: 0,
            ),
            e: NutrientModel(
              value: 2.5,
              unit: 'mg',
              label: 'Vitamin E',
              dailyValue: 17,
            ),
            k: NutrientModel(
              value: 18,
              unit: 'mcg',
              label: 'Vitamin K',
              dailyValue: 15,
            ),
            b1: NutrientModel(
              value: 0.5,
              unit: 'mg',
              label: 'Thiamin',
              dailyValue: 42,
            ),
            b2: NutrientModel(
              value: 0.4,
              unit: 'mg',
              label: 'Riboflavin',
              dailyValue: 31,
            ),
            b3: NutrientModel(
              value: 15,
              unit: 'mg',
              label: 'Niacin',
              dailyValue: 94,
            ),
            b6: NutrientModel(
              value: 1.4,
              unit: 'mg',
              label: 'Vitamin B6',
              dailyValue: 82,
            ),
            b12: NutrientModel(
              value: 0.8,
              unit: 'mcg',
              label: 'Vitamin B12',
              dailyValue: 33,
            ),
            folate: NutrientModel(
              value: 42,
              unit: 'mcg',
              label: 'Folate',
              dailyValue: 11,
            ),
          ),
          minerals: MineralModel(
            calcium: NutrientModel(
              value: 45,
              unit: 'mg',
              label: 'Calcium',
              dailyValue: 5,
            ),
            iron: NutrientModel(
              value: 3.2,
              unit: 'mg',
              label: 'Iron',
              dailyValue: 18,
            ),
            magnesium: NutrientModel(
              value: 85,
              unit: 'mg',
              label: 'Magnesium',
              dailyValue: 20,
            ),
            phosphorus: NutrientModel(
              value: 380,
              unit: 'mg',
              label: 'Phosphorus',
              dailyValue: 30,
            ),
            potassium: NutrientModel(
              value: 720,
              unit: 'mg',
              label: 'Potassium',
              dailyValue: 15,
            ),
            zinc: NutrientModel(
              value: 2.8,
              unit: 'mg',
              label: 'Zinc',
              dailyValue: 25,
            ),
            selenium: NutrientModel(
              value: 32,
              unit: 'mcg',
              label: 'Selenium',
              dailyValue: 58,
            ),
          ),
          dietLabels: ['High-Protein', 'Balanced'],
          healthLabels: ['Gluten-Free'],
          cautions: ['Spicy'],
          servingWeight: 450,
          servings: 1,
        ),
        mealType: 'lunch',
        tags: ['chicken', 'mexican', 'spicy'],
        rating: 4.7,
        isFavorite: true,
      ),
    ];
  }
}

</file>
<file path="lib/features/recipes/data/repositories/recipe_repository_impl.dart">
import 'package:dartz/dartz.dart';

import '../../../../core/errors/exceptions.dart';
import '../../../../core/errors/failures.dart';
import '../../domain/entities/recipe.dart';
import '../../domain/repositories/recipe_repository.dart';
import '../models/recipe_model.dart';

/// Implementation of Recipe Repository
class RecipeRepositoryImpl implements RecipeRepository {
  // In a real app, this would use a data source (API or local)

  @override
  Future<Either<Failure, List<Recipe>>> getAllRecipes() async {
    try {
      // This is a mock implementation using hardcoded data
      final recipes = RecipeModel.getMockRecipes();
      return Right(recipes);
    } on ServerException {
      return Left(ServerFailure('Failed to fetch recipes from server'));
    } catch (e) {
      return Left(GeneralFailure());
    }
  }

  @override
  Future<Either<Failure, List<Recipe>>> getRecipesByMealType(
    String mealType,
  ) async {
    try {
      final allRecipes = RecipeModel.getMockRecipes();
      final filtered = allRecipes
          .where(
            (recipe) =>
                recipe.mealType?.toLowerCase() == mealType.toLowerCase(),
          )
          .toList();
      return Right(filtered);
    } on ServerException {
      return Left(ServerFailure('Failed to fetch recipes by meal type'));
    } catch (e) {
      return Left(GeneralFailure());
    }
  }

  @override
  Future<Either<Failure, Recipe>> getRecipeById(String id) async {
    try {
      final allRecipes = RecipeModel.getMockRecipes();
      final recipe = allRecipes.firstWhere((recipe) => recipe.id == id);
      return Right(recipe);
    } on ServerException {
      return Left(ServerFailure('Failed to fetch recipe details'));
    } catch (e) {
      return Left(NotFoundFailure('Recipe not found'));
    }
  }

  @override
  Future<Either<Failure, List<Recipe>>> getFavoriteRecipes() async {
    try {
      final allRecipes = RecipeModel.getMockRecipes();
      // Just returning a subset for now as mock favorites
      final favorites = allRecipes.sublist(0, 2);
      return Right(favorites);
    } on ServerException {
      return Left(ServerFailure('Failed to fetch favorite recipes'));
    } catch (e) {
      return Left(GeneralFailure());
    }
  }

  @override
  Future<Either<Failure, Recipe>> toggleFavorite(String id) async {
    try {
      final allRecipes = RecipeModel.getMockRecipes();
      final recipeIndex = allRecipes.indexWhere((recipe) => recipe.id == id);

      if (recipeIndex >= 0) {
        final recipe = allRecipes[recipeIndex];
        final updatedRecipe = recipe.copyWith(isFavorite: !recipe.isFavorite);
        return Right(updatedRecipe);
      }

      throw Exception('Recipe not found');
    } on ServerException {
      return Left(ServerFailure('Failed to update favorite status'));
    } catch (e) {
      return Left(NotFoundFailure('Recipe not found'));
    }
  }

  @override
  Future<Either<Failure, List<Recipe>>> searchRecipes(String query) async {
    try {
      if (query.isEmpty) {
        return getAllRecipes();
      }

      final allRecipes = RecipeModel.getMockRecipes();
      final filtered = allRecipes
          .where(
            (recipe) =>
                recipe.name.toLowerCase().contains(query.toLowerCase()) ||
                (recipe.description?.toLowerCase().contains(
                      query.toLowerCase(),
                    ) ??
                    false),
          )
          .toList();

      return Right(filtered);
    } on ServerException {
      return Left(ServerFailure('Failed to search recipes'));
    } catch (e) {
      return Left(GeneralFailure());
    }
  }
}

</file>
<file path="lib/features/recipes/domain/entities/recipe.dart">
import 'package:equatable/equatable.dart';
import '../../../../features/nutrition/data/models/nutrition_model.dart';

/// Recipe entity representing a food recipe
class Recipe extends Equatable {
  final String id;
  final String name;
  final String? description;
  final String? imageUrl;
  final int prepTimeMinutes;
  final int cookTimeMinutes;
  final int servings;
  final List<String> ingredients;
  final List<String> instructions;
  final NutritionModel nutrition;
  final String? mealType; // breakfast, lunch, dinner, snack
  final List<String>? tags;
  final double? rating;
  final bool isFavorite;

  const Recipe({
    required this.id,
    required this.name,
    this.description,
    this.imageUrl,
    required this.prepTimeMinutes,
    required this.cookTimeMinutes,
    required this.servings,
    required this.ingredients,
    required this.instructions,
    required this.nutrition,
    this.mealType,
    this.tags,
    this.rating,
    this.isFavorite = false,
  });

  int get totalTimeMinutes => prepTimeMinutes + cookTimeMinutes;

  Recipe copyWith({
    String? id,
    String? name,
    String? description,
    String? imageUrl,
    int? prepTimeMinutes,
    int? cookTimeMinutes,
    int? servings,
    List<String>? ingredients,
    List<String>? instructions,
    NutritionModel? nutrition,
    String? mealType,
    List<String>? tags,
    double? rating,
    bool? isFavorite,
  }) {
    return Recipe(
      id: id ?? this.id,
      name: name ?? this.name,
      description: description ?? this.description,
      imageUrl: imageUrl ?? this.imageUrl,
      prepTimeMinutes: prepTimeMinutes ?? this.prepTimeMinutes,
      cookTimeMinutes: cookTimeMinutes ?? this.cookTimeMinutes,
      servings: servings ?? this.servings,
      ingredients: ingredients ?? this.ingredients,
      instructions: instructions ?? this.instructions,
      nutrition: nutrition ?? this.nutrition,
      mealType: mealType ?? this.mealType,
      tags: tags ?? this.tags,
      rating: rating ?? this.rating,
      isFavorite: isFavorite ?? this.isFavorite,
    );
  }

  @override
  List<Object?> get props => [
    id,
    name,
    description,
    imageUrl,
    prepTimeMinutes,
    cookTimeMinutes,
    servings,
    ingredients,
    instructions,
    nutrition,
    mealType,
    tags,
    rating,
    isFavorite,
  ];
}

</file>
<file path="lib/features/recipes/domain/repositories/recipe_repository.dart">
import 'package:dartz/dartz.dart';

import '../../../../core/errors/failures.dart';
import '../entities/recipe.dart';

/// Repository interface for recipes
abstract class RecipeRepository {
  /// Fetches all recipes
  Future<Either<Failure, List<Recipe>>> getAllRecipes();

  /// Fetches recipes by meal type
  Future<Either<Failure, List<Recipe>>> getRecipesByMealType(String mealType);

  /// Fetches a recipe by id
  Future<Either<Failure, Recipe>> getRecipeById(String id);

  /// Fetches favorite recipes
  Future<Either<Failure, List<Recipe>>> getFavoriteRecipes();

  /// Toggles a recipe's favorite status
  Future<Either<Failure, Recipe>> toggleFavorite(String id);

  /// Searches for recipes by name
  Future<Either<Failure, List<Recipe>>> searchRecipes(String query);
}

</file>
<file path="lib/features/recipes/domain/usecases/recipe_usecases.dart">
import 'package:dartz/dartz.dart';
import 'package:equatable/equatable.dart';

import '../../../../core/errors/failures.dart';
import '../entities/recipe.dart';
import '../repositories/recipe_repository.dart';

/// Abstract use case with parameters
abstract class UseCase<Type, Params> {
  Future<Either<Failure, Type>> call(Params params);
}

/// No parameters for use cases
class NoParams extends Equatable {
  @override
  List<Object?> get props => [];
}

/// Get Recipe By Id use case
class GetRecipeById extends UseCase<Recipe, RecipeParams> {
  final RecipeRepository repository;

  GetRecipeById(this.repository);

  @override
  Future<Either<Failure, Recipe>> call(RecipeParams params) {
    return repository.getRecipeById(params.id);
  }
}

/// Parameters for recipe operations
class RecipeParams extends Equatable {
  final String id;

  const RecipeParams({required this.id});

  @override
  List<Object?> get props => [id];
}

/// Get All Recipes use case
class GetAllRecipes extends UseCase<List<Recipe>, NoParams> {
  final RecipeRepository repository;

  GetAllRecipes(this.repository);

  @override
  Future<Either<Failure, List<Recipe>>> call(NoParams params) {
    return repository.getAllRecipes();
  }
}

/// Get Recipes By Meal Type use case
class GetRecipesByMealType extends UseCase<List<Recipe>, MealTypeParams> {
  final RecipeRepository repository;

  GetRecipesByMealType(this.repository);

  @override
  Future<Either<Failure, List<Recipe>>> call(MealTypeParams params) {
    return repository.getRecipesByMealType(params.mealType);
  }
}

/// Parameters for meal type operations
class MealTypeParams extends Equatable {
  final String mealType;

  const MealTypeParams({required this.mealType});

  @override
  List<Object?> get props => [mealType];
}

/// Get Favorite Recipes use case
class GetFavoriteRecipes extends UseCase<List<Recipe>, NoParams> {
  final RecipeRepository repository;

  GetFavoriteRecipes(this.repository);

  @override
  Future<Either<Failure, List<Recipe>>> call(NoParams params) {
    return repository.getFavoriteRecipes();
  }
}

/// Toggle Favorite Recipe use case
class ToggleFavoriteRecipe extends UseCase<Recipe, RecipeParams> {
  final RecipeRepository repository;

  ToggleFavoriteRecipe(this.repository);

  @override
  Future<Either<Failure, Recipe>> call(RecipeParams params) {
    return repository.toggleFavorite(params.id);
  }
}

/// Search Recipes use case
class SearchRecipes extends UseCase<List<Recipe>, SearchParams> {
  final RecipeRepository repository;

  SearchRecipes(this.repository);

  @override
  Future<Either<Failure, List<Recipe>>> call(SearchParams params) {
    return repository.searchRecipes(params.query);
  }
}

/// Parameters for search operations
class SearchParams extends Equatable {
  final String query;

  const SearchParams({required this.query});

  @override
  List<Object?> get props => [query];
}

</file>
<file path="lib/features/recipes/presentation/bloc/recipe_detail_bloc.dart">
import 'package:equatable/equatable.dart';
import 'package:flutter_bloc/flutter_bloc.dart';

import '../../domain/entities/recipe.dart';
import '../../domain/usecases/recipe_usecases.dart';

part 'recipe_detail_event.dart';
part 'recipe_detail_state.dart';

/// BLoC for recipe details
class RecipeDetailBloc extends Bloc<RecipeDetailEvent, RecipeDetailState> {
  final GetRecipeById getRecipeById;
  final ToggleFavoriteRecipe toggleFavoriteRecipe;

  RecipeDetailBloc({
    required this.getRecipeById,
    required this.toggleFavoriteRecipe,
  }) : super(RecipeDetailInitial()) {
    on<LoadRecipeDetail>(_onLoadRecipeDetail);
    on<ToggleFavoriteStatus>(_onToggleFavoriteStatus);
  }

  Future<void> _onLoadRecipeDetail(
    LoadRecipeDetail event,
    Emitter<RecipeDetailState> emit,
  ) async {
    emit(RecipeDetailLoading());

    final result = await getRecipeById(RecipeParams(id: event.recipeId));

    result.fold(
      (failure) => emit(RecipeDetailError(message: failure.message)),
      (recipe) => emit(RecipeDetailLoaded(recipe: recipe)),
    );
  }

  Future<void> _onToggleFavoriteStatus(
    ToggleFavoriteStatus event,
    Emitter<RecipeDetailState> emit,
  ) async {
    if (state is RecipeDetailLoaded) {
      final currentState = state as RecipeDetailLoaded;
      final recipe = currentState.recipe;

      // Optimistically update UI
      emit(
        RecipeDetailLoaded(
          recipe: recipe.copyWith(isFavorite: !recipe.isFavorite),
        ),
      );

      final result = await toggleFavoriteRecipe(RecipeParams(id: recipe.id));

      // Handle any errors by going back to the original state
      result.fold(
        (failure) => emit(RecipeDetailLoaded(recipe: recipe)),
        (_) => null, // Success case already handled optimistically
      );
    }
  }
}

</file>
<file path="lib/features/recipes/presentation/bloc/recipe_detail_event.dart">
part of 'recipe_detail_bloc.dart';

/// Base class for recipe detail events
abstract class RecipeDetailEvent extends Equatable {
  const RecipeDetailEvent();

  @override
  List<Object> get props => [];
}

/// Event to load recipe details
class LoadRecipeDetail extends RecipeDetailEvent {
  final String recipeId;

  const LoadRecipeDetail({required this.recipeId});

  @override
  List<Object> get props => [recipeId];
}

/// Event to toggle a recipe's favorite status
class ToggleFavoriteStatus extends RecipeDetailEvent {
  const ToggleFavoriteStatus();
}

</file>
<file path="lib/features/recipes/presentation/bloc/recipe_detail_state.dart">
part of 'recipe_detail_bloc.dart';

/// Base class for recipe detail states
abstract class RecipeDetailState extends Equatable {
  const RecipeDetailState();

  @override
  List<Object> get props => [];
}

/// Initial state
class RecipeDetailInitial extends RecipeDetailState {}

/// Loading state
class RecipeDetailLoading extends RecipeDetailState {}

/// Loaded state with recipe data
class RecipeDetailLoaded extends RecipeDetailState {
  final Recipe recipe;

  const RecipeDetailLoaded({required this.recipe});

  @override
  List<Object> get props => [recipe];
}

/// Error state
class RecipeDetailError extends RecipeDetailState {
  final String message;

  const RecipeDetailError({required this.message});

  @override
  List<Object> get props => [message];
}

</file>
<file path="lib/features/recipes/presentation/pages/recipe_detail_page.dart">
import 'package:cached_network_image/cached_network_image.dart';
import 'package:flutter/material.dart';
import '../../../../core/theme/app_theme.dart';
import '../../../../features/nutrition/presentation/widgets/nutrition_bottom_sheet.dart';
import '../../data/models/recipe_model.dart';
import '../../domain/entities/recipe.dart';

/// Recipe details page
class RecipeDetailPage extends StatefulWidget {
  final String recipeId;

  const RecipeDetailPage({super.key, required this.recipeId});

  @override
  State<RecipeDetailPage> createState() => _RecipeDetailPageState();
}

class _RecipeDetailPageState extends State<RecipeDetailPage> {
  late Recipe recipe;
  bool isLoading = true;
  String? errorMessage;

  @override
  void initState() {
    super.initState();
    _loadRecipe();
  }

  void _loadRecipe() async {
    try {
      // In a real app, this would fetch from the repository
      final recipes = RecipeModel.getMockRecipes();
      recipe = recipes.firstWhere(
        (r) => r.id == widget.recipeId,
        orElse: () => throw Exception('Recipe not found'),
      );
      setState(() {
        isLoading = false;
      });
    } catch (e) {
      setState(() {
        isLoading = false;
        errorMessage = e.toString();
      });
    }
  }

  void _toggleFavorite() {
    setState(() {
      recipe = recipe.copyWith(isFavorite: !recipe.isFavorite);
    });
  }

  void _showNutritionSheet(Recipe recipe) {
    showModalBottomSheet(
      context: context,
      isScrollControlled: true,
      useRootNavigator: true,
      backgroundColor: Colors.transparent,
      builder: (context) => NutritionBottomSheet(
        title: '${recipe.name} Nutrition',
        nutrition: recipe.nutrition,
      ),
    );
  }

  @override
  Widget build(BuildContext context) {
    return Scaffold(
      backgroundColor: AppTheme.darkNavy,
      body: isLoading
          ? const Center(
              child: CircularProgressIndicator(color: AppTheme.primaryOrange),
            )
          : errorMessage != null
          ? Center(
              child: Text(
                'Error: $errorMessage',
                style: TextStyle(color: AppTheme.white),
              ),
            )
          : _buildDetailView(context, recipe),
    );
  }

  Widget _buildDetailView(BuildContext context, Recipe recipe) {
    return CustomScrollView(
      slivers: [
        _buildAppBar(context, recipe),
        SliverToBoxAdapter(
          child: Padding(
            padding: const EdgeInsets.all(16),
            child: Column(
              crossAxisAlignment: CrossAxisAlignment.start,
              children: [
                const SizedBox(height: 16),
                _buildTimeServingsRow(recipe),
                const SizedBox(height: 24),
                _buildNutritionInfo(recipe),
                const SizedBox(height: 32),
                _buildSectionHeader('Ingredients'),
                const SizedBox(height: 8),
                ...recipe.ingredients.map(
                  (ingredient) => _buildIngredientItem(ingredient),
                ),
                const SizedBox(height: 32),
                _buildSectionHeader('Instructions'),
                const SizedBox(height: 8),
                ...List.generate(
                  recipe.instructions.length,
                  (index) => _buildInstructionItem(
                    index + 1,
                    recipe.instructions[index],
                  ),
                ),
                const SizedBox(height: 40),
              ],
            ),
          ),
        ),
      ],
    );
  }

  Widget _buildAppBar(BuildContext context, Recipe recipe) {
    return SliverAppBar(
      expandedHeight: 240,
      pinned: true,
      backgroundColor: AppTheme.darkNavy,
      flexibleSpace: FlexibleSpaceBar(
        background: Stack(
          fit: StackFit.expand,
          children: [
            // Recipe image
            recipe.imageUrl != null
                ? CachedNetworkImage(
                    imageUrl: recipe.imageUrl!,
                    fit: BoxFit.cover,
                    placeholder: (context, url) => Container(
                      color: AppTheme.slate,
                      child: const Center(
                        child: CircularProgressIndicator(
                          color: AppTheme.primaryOrange,
                        ),
                      ),
                    ),
                    errorWidget: (context, url, error) => Container(
                      color: AppTheme.slate,
                      child: const Icon(
                        Icons.restaurant,
                        size: 60,
                        color: AppTheme.mediumGray,
                      ),
                    ),
                  )
                : Container(
                    color: AppTheme.slate,
                    child: const Icon(
                      Icons.restaurant,
                      size: 60,
                      color: AppTheme.mediumGray,
                    ),
                  ),
            // Gradient overlay for better readability
            Container(
              decoration: BoxDecoration(
                gradient: LinearGradient(
                  begin: Alignment.topCenter,
                  end: Alignment.bottomCenter,
                  colors: [
                    Colors.transparent,
                    AppTheme.darkNavy.withOpacity(0.8),
                  ],
                ),
              ),
            ),
          ],
        ),
      ),
      title: Text(
        recipe.name,
        style: TextStyle(color: AppTheme.white, fontWeight: FontWeight.bold),
      ),
      actions: [
        IconButton(
          icon: Icon(
            recipe.isFavorite ? Icons.favorite : Icons.favorite_border,
            color: recipe.isFavorite ? AppTheme.primaryCoral : AppTheme.white,
          ),
          onPressed: () {
            _toggleFavorite();
          },
        ),
      ],
    );
  }

  Widget _buildTimeServingsRow(Recipe recipe) {
    return Row(
      mainAxisAlignment: MainAxisAlignment.spaceBetween,
      children: [
        _buildInfoItem(
          icon: Icons.timer,
          label: 'Prep Time',
          value: '${recipe.prepTimeMinutes} min',
        ),
        _buildInfoItem(
          icon: Icons.local_fire_department,
          label: 'Cook Time',
          value: '${recipe.cookTimeMinutes} min',
        ),
        _buildInfoItem(
          icon: Icons.restaurant,
          label: 'Servings',
          value: '${recipe.servings}',
        ),
        _buildInfoItem(
          icon: Icons.timer,
          label: 'Total Time',
          value: '${recipe.totalTimeMinutes} min',
        ),
      ],
    );
  }

  Widget _buildInfoItem({
    required IconData icon,
    required String label,
    required String value,
  }) {
    return Column(
      children: [
        Icon(icon, color: AppTheme.primaryOrange, size: 24),
        const SizedBox(height: 8),
        Text(
          value,
          style: TextStyle(
            color: AppTheme.white,
            fontSize: 16,
            fontWeight: FontWeight.w600,
          ),
        ),
        const SizedBox(height: 4),
        Text(label, style: TextStyle(color: AppTheme.mediumGray, fontSize: 12)),
      ],
    );
  }

  Widget _buildNutrient({
    required String label,
    required dynamic value,
    required Color color,
  }) {
    return Column(
      children: [
        Text(
          value is String ? value : value.toString(),
          style: TextStyle(
            color: color,
            fontSize: 18,
            fontWeight: FontWeight.w600,
          ),
        ),
        const SizedBox(height: 4),
        Text(label, style: TextStyle(color: AppTheme.mediumGray, fontSize: 12)),
      ],
    );
  }

  Widget _buildNutritionInfo(Recipe recipe) {
    return Column(
      children: [
        Container(
          padding: const EdgeInsets.all(16),
          decoration: BoxDecoration(
            color: AppTheme.charcoal,
            borderRadius: BorderRadius.circular(16),
          ),
          child: Row(
            mainAxisAlignment: MainAxisAlignment.spaceAround,
            children: [
              _buildNutrient(
                label: 'Calories',
                value: '${recipe.nutrition.calories}',
                color: AppTheme.primaryOrange,
              ),
              _buildNutrient(
                label: 'Protein',
                value:
                    '${recipe.nutrition.protein.value}${recipe.nutrition.protein.unit}',
                color: AppTheme.accentYellow,
              ),
              _buildNutrient(
                label: 'Carbs',
                value:
                    '${recipe.nutrition.carbs.value}${recipe.nutrition.carbs.unit}',
                color: AppTheme.primaryCoral,
              ),
              _buildNutrient(
                label: 'Fat',
                value:
                    '${recipe.nutrition.fat.value}${recipe.nutrition.fat.unit}',
                color: AppTheme.accentPurple,
              ),
            ],
          ),
        ),
        const SizedBox(height: 12),
        InkWell(
          onTap: () => _showNutritionSheet(recipe),
          child: Container(
            padding: const EdgeInsets.symmetric(vertical: 10, horizontal: 16),
            decoration: BoxDecoration(
              border: Border.all(color: AppTheme.primaryOrange),
              borderRadius: BorderRadius.circular(8),
            ),
            child: Row(
              mainAxisAlignment: MainAxisAlignment.center,
              children: [
                Text(
                  'See Full Nutrition',
                  style: TextStyle(
                    color: AppTheme.primaryOrange,
                    fontWeight: FontWeight.w600,
                  ),
                ),
                const SizedBox(width: 8),
                Icon(
                  Icons.arrow_forward_rounded,
                  color: AppTheme.primaryOrange,
                  size: 18,
                ),
              ],
            ),
          ),
        ),
      ],
    );
  }

  Widget _buildSectionHeader(String title) {
    return Text(
      title,
      style: TextStyle(
        color: AppTheme.white,
        fontSize: 20,
        fontWeight: FontWeight.w700,
      ),
    );
  }

  Widget _buildIngredientItem(String ingredient) {
    return Padding(
      padding: const EdgeInsets.symmetric(vertical: 8),
      child: Row(
        crossAxisAlignment: CrossAxisAlignment.start,
        children: [
          Container(
            margin: const EdgeInsets.only(top: 6),
            height: 8,
            width: 8,
            decoration: const BoxDecoration(
              color: AppTheme.primaryOrange,
              shape: BoxShape.circle,
            ),
          ),
          const SizedBox(width: 12),
          Expanded(
            child: Text(
              ingredient,
              style: TextStyle(
                color: AppTheme.white.withOpacity(0.9),
                fontSize: 16,
                height: 1.5,
              ),
            ),
          ),
        ],
      ),
    );
  }

  Widget _buildInstructionItem(int stepNumber, String instruction) {
    return Padding(
      padding: const EdgeInsets.symmetric(vertical: 12),
      child: Row(
        crossAxisAlignment: CrossAxisAlignment.start,
        children: [
          Container(
            height: 28,
            width: 28,
            decoration: BoxDecoration(
              color: AppTheme.primaryOrange.withOpacity(0.2),
              shape: BoxShape.circle,
            ),
            child: Center(
              child: Text(
                '$stepNumber',
                style: TextStyle(
                  color: AppTheme.primaryOrange,
                  fontWeight: FontWeight.bold,
                ),
              ),
            ),
          ),
          const SizedBox(width: 12),
          Expanded(
            child: Text(
              instruction,
              style: TextStyle(
                color: AppTheme.white.withOpacity(0.9),
                fontSize: 16,
                height: 1.5,
              ),
            ),
          ),
        ],
      ),
    );
  }
}

</file>
<file path="lib/main.dart">
import 'package:flutter/material.dart';
import 'package:flutter/services.dart';
import 'package:flutter_bloc/flutter_bloc.dart';
import 'package:hive_flutter/hive_flutter.dart';

import 'features/budget_tracking/presentation/bloc/budget_bloc.dart';
import 'features/nutrition/presentation/bloc/nutrition_bloc.dart';
import 'features/recipes/presentation/bloc/recipe_detail_bloc.dart';
import 'mcp_setup.dart';
import 'core/config/app_config.dart';
import 'core/di/injection_container.dart' as di;
import 'core/network/supabase_service.dart';
import 'core/routes/app_router.dart';
import 'core/theme/app_theme.dart';

void main() async {
  WidgetsFlutterBinding.ensureInitialized();

  // Initialize MCP for development
  await initializeMcp();

  // Set preferred orientations
  await SystemChrome.setPreferredOrientations([
    DeviceOrientation.portraitUp,
    DeviceOrientation.portraitDown,
  ]);

  // Set status bar appearance
  SystemChrome.setSystemUIOverlayStyle(
    const SystemUiOverlayStyle(
      statusBarColor: Colors.transparent,
      statusBarIconBrightness: Brightness.light,
      systemNavigationBarColor: AppTheme.darkNavy,
      systemNavigationBarIconBrightness: Brightness.light,
    ),
  );

  // Initialize services
  await Future.wait([AppConfig.init(), Hive.initFlutter()]);

  // Initialize Supabase
  await SupabaseService.initialize();

  // Initialize dependency injection
  await di.init();

  runApp(const FoodsterApp());
}

class FoodsterApp extends StatelessWidget {
  const FoodsterApp({super.key});

  @override
  Widget build(BuildContext context) {
    return MultiBlocProvider(
      providers: [
        BlocProvider<BudgetBloc>(create: (context) => di.sl<BudgetBloc>()),
        BlocProvider<NutritionBloc>(
          create: (context) => di.sl<NutritionBloc>(),
        ),
        BlocProvider<RecipeDetailBloc>(
          create: (context) => di.sl<RecipeDetailBloc>(),
        ),
      ],
      child: KeyboardDismissible(
        child: MaterialApp(
          title: 'Foodster',
          debugShowCheckedModeBanner: false,
          theme: AppTheme.darkTheme,
          initialRoute: _getInitialRoute(),
          onGenerateRoute: AppRouter.generateRoute,
        ),
      ),
    );
  }

  String _getInitialRoute() {
    final user = SupabaseService.currentUser;
    return user != null ? AppRouter.dashboard : AppRouter.splash;
  }
}

class KeyboardDismissible extends StatelessWidget {
  final Widget child;

  const KeyboardDismissible({super.key, required this.child});

  @override
  Widget build(BuildContext context) {
    return GestureDetector(
      onTap: () {
        FocusScope.of(context).unfocus();
      },
      child: child,
    );
  }
}

</file>
<file path="lib/mcp_setup.dart">
import 'package:flutter/foundation.dart';

/// Initialize MCP server for development
Future<void> initializeMcp() async {
  // Only initialize MCP in debug mode and when enabled
  if (kDebugMode &&
      const bool.fromEnvironment('USE_MCP_SERVER', defaultValue: false)) {
    print('MCP support is enabled. Starting server...');
  }
}

</file>
<file path="linux/flutter/ephemeral/.plugin_symlinks/app_links_linux">
Error reading file: read /Users/varyable/Workspace/mobileapps/foodster/linux/flutter/ephemeral/.plugin_symlinks/app_links_linux: is a directory
</file>
<file path="linux/flutter/ephemeral/.plugin_symlinks/connectivity_plus">
Error reading file: read /Users/varyable/Workspace/mobileapps/foodster/linux/flutter/ephemeral/.plugin_symlinks/connectivity_plus: is a directory
</file>
<file path="linux/flutter/ephemeral/.plugin_symlinks/flutter_secure_storage_linux">
Error reading file: read /Users/varyable/Workspace/mobileapps/foodster/linux/flutter/ephemeral/.plugin_symlinks/flutter_secure_storage_linux: is a directory
</file>
<file path="linux/flutter/ephemeral/.plugin_symlinks/gtk">
Error reading file: read /Users/varyable/Workspace/mobileapps/foodster/linux/flutter/ephemeral/.plugin_symlinks/gtk: is a directory
</file>
<file path="linux/flutter/ephemeral/.plugin_symlinks/path_provider_linux">
Error reading file: read /Users/varyable/Workspace/mobileapps/foodster/linux/flutter/ephemeral/.plugin_symlinks/path_provider_linux: is a directory
</file>
<file path="linux/flutter/ephemeral/.plugin_symlinks/shared_preferences_linux">
Error reading file: read /Users/varyable/Workspace/mobileapps/foodster/linux/flutter/ephemeral/.plugin_symlinks/shared_preferences_linux: is a directory
</file>
<file path="linux/flutter/ephemeral/.plugin_symlinks/url_launcher_linux">
Error reading file: read /Users/varyable/Workspace/mobileapps/foodster/linux/flutter/ephemeral/.plugin_symlinks/url_launcher_linux: is a directory
</file>
<file path="linux/flutter/CMakeLists.txt">
# This file controls Flutter-level build steps. It should not be edited.
cmake_minimum_required(VERSION 3.10)

set(EPHEMERAL_DIR "${CMAKE_CURRENT_SOURCE_DIR}/ephemeral")

# Configuration provided via flutter tool.
include(${EPHEMERAL_DIR}/generated_config.cmake)

# TODO: Move the rest of this into files in ephemeral. See
# https://github.com/flutter/flutter/issues/57146.

# Serves the same purpose as list(TRANSFORM ... PREPEND ...),
# which isn't available in 3.10.
function(list_prepend LIST_NAME PREFIX)
    set(NEW_LIST "")
    foreach(element ${${LIST_NAME}})
        list(APPEND NEW_LIST "${PREFIX}${element}")
    endforeach(element)
    set(${LIST_NAME} "${NEW_LIST}" PARENT_SCOPE)
endfunction()

# === Flutter Library ===
# System-level dependencies.
find_package(PkgConfig REQUIRED)
pkg_check_modules(GTK REQUIRED IMPORTED_TARGET gtk+-3.0)
pkg_check_modules(GLIB REQUIRED IMPORTED_TARGET glib-2.0)
pkg_check_modules(GIO REQUIRED IMPORTED_TARGET gio-2.0)

set(FLUTTER_LIBRARY "${EPHEMERAL_DIR}/libflutter_linux_gtk.so")

# Published to parent scope for install step.
set(FLUTTER_LIBRARY ${FLUTTER_LIBRARY} PARENT_SCOPE)
set(FLUTTER_ICU_DATA_FILE "${EPHEMERAL_DIR}/icudtl.dat" PARENT_SCOPE)
set(PROJECT_BUILD_DIR "${PROJECT_DIR}/build/" PARENT_SCOPE)
set(AOT_LIBRARY "${PROJECT_DIR}/build/lib/libapp.so" PARENT_SCOPE)

list(APPEND FLUTTER_LIBRARY_HEADERS
  "fl_basic_message_channel.h"
  "fl_binary_codec.h"
  "fl_binary_messenger.h"
  "fl_dart_project.h"
  "fl_engine.h"
  "fl_json_message_codec.h"
  "fl_json_method_codec.h"
  "fl_message_codec.h"
  "fl_method_call.h"
  "fl_method_channel.h"
  "fl_method_codec.h"
  "fl_method_response.h"
  "fl_plugin_registrar.h"
  "fl_plugin_registry.h"
  "fl_standard_message_codec.h"
  "fl_standard_method_codec.h"
  "fl_string_codec.h"
  "fl_value.h"
  "fl_view.h"
  "flutter_linux.h"
)
list_prepend(FLUTTER_LIBRARY_HEADERS "${EPHEMERAL_DIR}/flutter_linux/")
add_library(flutter INTERFACE)
target_include_directories(flutter INTERFACE
  "${EPHEMERAL_DIR}"
)
target_link_libraries(flutter INTERFACE "${FLUTTER_LIBRARY}")
target_link_libraries(flutter INTERFACE
  PkgConfig::GTK
  PkgConfig::GLIB
  PkgConfig::GIO
)
add_dependencies(flutter flutter_assemble)

# === Flutter tool backend ===
# _phony_ is a non-existent file to force this command to run every time,
# since currently there's no way to get a full input/output list from the
# flutter tool.
add_custom_command(
  OUTPUT ${FLUTTER_LIBRARY} ${FLUTTER_LIBRARY_HEADERS}
    ${CMAKE_CURRENT_BINARY_DIR}/_phony_
  COMMAND ${CMAKE_COMMAND} -E env
    ${FLUTTER_TOOL_ENVIRONMENT}
    "${FLUTTER_ROOT}/packages/flutter_tools/bin/tool_backend.sh"
      ${FLUTTER_TARGET_PLATFORM} ${CMAKE_BUILD_TYPE}
  VERBATIM
)
add_custom_target(flutter_assemble DEPENDS
  "${FLUTTER_LIBRARY}"
  ${FLUTTER_LIBRARY_HEADERS}
)

</file>
<file path="linux/flutter/generated_plugin_registrant.cc">
//
//  Generated file. Do not edit.
//

// clang-format off

#include "generated_plugin_registrant.h"

#include <flutter_secure_storage_linux/flutter_secure_storage_linux_plugin.h>
#include <gtk/gtk_plugin.h>
#include <url_launcher_linux/url_launcher_plugin.h>

void fl_register_plugins(FlPluginRegistry* registry) {
  g_autoptr(FlPluginRegistrar) flutter_secure_storage_linux_registrar =
      fl_plugin_registry_get_registrar_for_plugin(registry, "FlutterSecureStorageLinuxPlugin");
  flutter_secure_storage_linux_plugin_register_with_registrar(flutter_secure_storage_linux_registrar);
  g_autoptr(FlPluginRegistrar) gtk_registrar =
      fl_plugin_registry_get_registrar_for_plugin(registry, "GtkPlugin");
  gtk_plugin_register_with_registrar(gtk_registrar);
  g_autoptr(FlPluginRegistrar) url_launcher_linux_registrar =
      fl_plugin_registry_get_registrar_for_plugin(registry, "UrlLauncherPlugin");
  url_launcher_plugin_register_with_registrar(url_launcher_linux_registrar);
}

</file>
<file path="linux/flutter/generated_plugin_registrant.h">
//
//  Generated file. Do not edit.
//

// clang-format off

#ifndef GENERATED_PLUGIN_REGISTRANT_
#define GENERATED_PLUGIN_REGISTRANT_

#include <flutter_linux/flutter_linux.h>

// Registers Flutter plugins.
void fl_register_plugins(FlPluginRegistry* registry);

#endif  // GENERATED_PLUGIN_REGISTRANT_

</file>
<file path="linux/flutter/generated_plugins.cmake">
#
# Generated file, do not edit.
#

list(APPEND FLUTTER_PLUGIN_LIST
  flutter_secure_storage_linux
  gtk
  url_launcher_linux
)

list(APPEND FLUTTER_FFI_PLUGIN_LIST
)

set(PLUGIN_BUNDLED_LIBRARIES)

foreach(plugin ${FLUTTER_PLUGIN_LIST})
  add_subdirectory(flutter/ephemeral/.plugin_symlinks/${plugin}/linux plugins/${plugin})
  target_link_libraries(${BINARY_NAME} PRIVATE ${plugin}_plugin)
  list(APPEND PLUGIN_BUNDLED_LIBRARIES $<TARGET_FILE:${plugin}_plugin>)
  list(APPEND PLUGIN_BUNDLED_LIBRARIES ${${plugin}_bundled_libraries})
endforeach(plugin)

foreach(ffi_plugin ${FLUTTER_FFI_PLUGIN_LIST})
  add_subdirectory(flutter/ephemeral/.plugin_symlinks/${ffi_plugin}/linux plugins/${ffi_plugin})
  list(APPEND PLUGIN_BUNDLED_LIBRARIES ${${ffi_plugin}_bundled_libraries})
endforeach(ffi_plugin)

</file>
<file path="linux/runner/CMakeLists.txt">
cmake_minimum_required(VERSION 3.13)
project(runner LANGUAGES CXX)

# Define the application target. To change its name, change BINARY_NAME in the
# top-level CMakeLists.txt, not the value here, or `flutter run` will no longer
# work.
#
# Any new source files that you add to the application should be added here.
add_executable(${BINARY_NAME}
  "main.cc"
  "my_application.cc"
  "${FLUTTER_MANAGED_DIR}/generated_plugin_registrant.cc"
)

# Apply the standard set of build settings. This can be removed for applications
# that need different build settings.
apply_standard_settings(${BINARY_NAME})

# Add preprocessor definitions for the application ID.
add_definitions(-DAPPLICATION_ID="${APPLICATION_ID}")

# Add dependency libraries. Add any application-specific dependencies here.
target_link_libraries(${BINARY_NAME} PRIVATE flutter)
target_link_libraries(${BINARY_NAME} PRIVATE PkgConfig::GTK)

target_include_directories(${BINARY_NAME} PRIVATE "${CMAKE_SOURCE_DIR}")

</file>
<file path="linux/runner/main.cc">
#include "my_application.h"

int main(int argc, char** argv) {
  g_autoptr(MyApplication) app = my_application_new();
  return g_application_run(G_APPLICATION(app), argc, argv);
}

</file>
<file path="linux/runner/my_application.cc">
#include "my_application.h"

#include <flutter_linux/flutter_linux.h>
#ifdef GDK_WINDOWING_X11
#include <gdk/gdkx.h>
#endif

#include "flutter/generated_plugin_registrant.h"

struct _MyApplication {
  GtkApplication parent_instance;
  char** dart_entrypoint_arguments;
};

G_DEFINE_TYPE(MyApplication, my_application, GTK_TYPE_APPLICATION)

// Implements GApplication::activate.
static void my_application_activate(GApplication* application) {
  MyApplication* self = MY_APPLICATION(application);
  GtkWindow* window =
      GTK_WINDOW(gtk_application_window_new(GTK_APPLICATION(application)));

  // Use a header bar when running in GNOME as this is the common style used
  // by applications and is the setup most users will be using (e.g. Ubuntu
  // desktop).
  // If running on X and not using GNOME then just use a traditional title bar
  // in case the window manager does more exotic layout, e.g. tiling.
  // If running on Wayland assume the header bar will work (may need changing
  // if future cases occur).
  gboolean use_header_bar = TRUE;
#ifdef GDK_WINDOWING_X11
  GdkScreen* screen = gtk_window_get_screen(window);
  if (GDK_IS_X11_SCREEN(screen)) {
    const gchar* wm_name = gdk_x11_screen_get_window_manager_name(screen);
    if (g_strcmp0(wm_name, "GNOME Shell") != 0) {
      use_header_bar = FALSE;
    }
  }
#endif
  if (use_header_bar) {
    GtkHeaderBar* header_bar = GTK_HEADER_BAR(gtk_header_bar_new());
    gtk_widget_show(GTK_WIDGET(header_bar));
    gtk_header_bar_set_title(header_bar, "foodster");
    gtk_header_bar_set_show_close_button(header_bar, TRUE);
    gtk_window_set_titlebar(window, GTK_WIDGET(header_bar));
  } else {
    gtk_window_set_title(window, "foodster");
  }

  gtk_window_set_default_size(window, 1280, 720);
  gtk_widget_show(GTK_WIDGET(window));

  g_autoptr(FlDartProject) project = fl_dart_project_new();
  fl_dart_project_set_dart_entrypoint_arguments(project, self->dart_entrypoint_arguments);

  FlView* view = fl_view_new(project);
  gtk_widget_show(GTK_WIDGET(view));
  gtk_container_add(GTK_CONTAINER(window), GTK_WIDGET(view));

  fl_register_plugins(FL_PLUGIN_REGISTRY(view));

  gtk_widget_grab_focus(GTK_WIDGET(view));
}

// Implements GApplication::local_command_line.
static gboolean my_application_local_command_line(GApplication* application, gchar*** arguments, int* exit_status) {
  MyApplication* self = MY_APPLICATION(application);
  // Strip out the first argument as it is the binary name.
  self->dart_entrypoint_arguments = g_strdupv(*arguments + 1);

  g_autoptr(GError) error = nullptr;
  if (!g_application_register(application, nullptr, &error)) {
     g_warning("Failed to register: %s", error->message);
     *exit_status = 1;
     return TRUE;
  }

  g_application_activate(application);
  *exit_status = 0;

  return TRUE;
}

// Implements GApplication::startup.
static void my_application_startup(GApplication* application) {
  //MyApplication* self = MY_APPLICATION(object);

  // Perform any actions required at application startup.

  G_APPLICATION_CLASS(my_application_parent_class)->startup(application);
}

// Implements GApplication::shutdown.
static void my_application_shutdown(GApplication* application) {
  //MyApplication* self = MY_APPLICATION(object);

  // Perform any actions required at application shutdown.

  G_APPLICATION_CLASS(my_application_parent_class)->shutdown(application);
}

// Implements GObject::dispose.
static void my_application_dispose(GObject* object) {
  MyApplication* self = MY_APPLICATION(object);
  g_clear_pointer(&self->dart_entrypoint_arguments, g_strfreev);
  G_OBJECT_CLASS(my_application_parent_class)->dispose(object);
}

static void my_application_class_init(MyApplicationClass* klass) {
  G_APPLICATION_CLASS(klass)->activate = my_application_activate;
  G_APPLICATION_CLASS(klass)->local_command_line = my_application_local_command_line;
  G_APPLICATION_CLASS(klass)->startup = my_application_startup;
  G_APPLICATION_CLASS(klass)->shutdown = my_application_shutdown;
  G_OBJECT_CLASS(klass)->dispose = my_application_dispose;
}

static void my_application_init(MyApplication* self) {}

MyApplication* my_application_new() {
  // Set the program name to the application ID, which helps various systems
  // like GTK and desktop environments map this running application to its
  // corresponding .desktop file. This ensures better integration by allowing
  // the application to be recognized beyond its binary name.
  g_set_prgname(APPLICATION_ID);

  return MY_APPLICATION(g_object_new(my_application_get_type(),
                                     "application-id", APPLICATION_ID,
                                     "flags", G_APPLICATION_NON_UNIQUE,
                                     nullptr));
}

</file>
<file path="linux/runner/my_application.h">
#ifndef FLUTTER_MY_APPLICATION_H_
#define FLUTTER_MY_APPLICATION_H_

#include <gtk/gtk.h>

G_DECLARE_FINAL_TYPE(MyApplication, my_application, MY, APPLICATION,
                     GtkApplication)

/**
 * my_application_new:
 *
 * Creates a new Flutter-based application.
 *
 * Returns: a new #MyApplication.
 */
MyApplication* my_application_new();

#endif  // FLUTTER_MY_APPLICATION_H_

</file>
<file path="linux/.gitignore">
flutter/ephemeral

</file>
<file path="linux/CMakeLists.txt">
# Project-level configuration.
cmake_minimum_required(VERSION 3.13)
project(runner LANGUAGES CXX)

# The name of the executable created for the application. Change this to change
# the on-disk name of your application.
set(BINARY_NAME "foodster")
# The unique GTK application identifier for this application. See:
# https://wiki.gnome.org/HowDoI/ChooseApplicationID
set(APPLICATION_ID "com.example.foodster")

# Explicitly opt in to modern CMake behaviors to avoid warnings with recent
# versions of CMake.
cmake_policy(SET CMP0063 NEW)

# Load bundled libraries from the lib/ directory relative to the binary.
set(CMAKE_INSTALL_RPATH "$ORIGIN/lib")

# Root filesystem for cross-building.
if(FLUTTER_TARGET_PLATFORM_SYSROOT)
  set(CMAKE_SYSROOT ${FLUTTER_TARGET_PLATFORM_SYSROOT})
  set(CMAKE_FIND_ROOT_PATH ${CMAKE_SYSROOT})
  set(CMAKE_FIND_ROOT_PATH_MODE_PROGRAM NEVER)
  set(CMAKE_FIND_ROOT_PATH_MODE_PACKAGE ONLY)
  set(CMAKE_FIND_ROOT_PATH_MODE_LIBRARY ONLY)
  set(CMAKE_FIND_ROOT_PATH_MODE_INCLUDE ONLY)
endif()

# Define build configuration options.
if(NOT CMAKE_BUILD_TYPE AND NOT CMAKE_CONFIGURATION_TYPES)
  set(CMAKE_BUILD_TYPE "Debug" CACHE
    STRING "Flutter build mode" FORCE)
  set_property(CACHE CMAKE_BUILD_TYPE PROPERTY STRINGS
    "Debug" "Profile" "Release")
endif()

# Compilation settings that should be applied to most targets.
#
# Be cautious about adding new options here, as plugins use this function by
# default. In most cases, you should add new options to specific targets instead
# of modifying this function.
function(APPLY_STANDARD_SETTINGS TARGET)
  target_compile_features(${TARGET} PUBLIC cxx_std_14)
  target_compile_options(${TARGET} PRIVATE -Wall -Werror)
  target_compile_options(${TARGET} PRIVATE "$<$<NOT:$<CONFIG:Debug>>:-O3>")
  target_compile_definitions(${TARGET} PRIVATE "$<$<NOT:$<CONFIG:Debug>>:NDEBUG>")
endfunction()

# Flutter library and tool build rules.
set(FLUTTER_MANAGED_DIR "${CMAKE_CURRENT_SOURCE_DIR}/flutter")
add_subdirectory(${FLUTTER_MANAGED_DIR})

# System-level dependencies.
find_package(PkgConfig REQUIRED)
pkg_check_modules(GTK REQUIRED IMPORTED_TARGET gtk+-3.0)

# Application build; see runner/CMakeLists.txt.
add_subdirectory("runner")

# Run the Flutter tool portions of the build. This must not be removed.
add_dependencies(${BINARY_NAME} flutter_assemble)

# Only the install-generated bundle's copy of the executable will launch
# correctly, since the resources must in the right relative locations. To avoid
# people trying to run the unbundled copy, put it in a subdirectory instead of
# the default top-level location.
set_target_properties(${BINARY_NAME}
  PROPERTIES
  RUNTIME_OUTPUT_DIRECTORY "${CMAKE_BINARY_DIR}/intermediates_do_not_run"
)


# Generated plugin build rules, which manage building the plugins and adding
# them to the application.
include(flutter/generated_plugins.cmake)


# === Installation ===
# By default, "installing" just makes a relocatable bundle in the build
# directory.
set(BUILD_BUNDLE_DIR "${PROJECT_BINARY_DIR}/bundle")
if(CMAKE_INSTALL_PREFIX_INITIALIZED_TO_DEFAULT)
  set(CMAKE_INSTALL_PREFIX "${BUILD_BUNDLE_DIR}" CACHE PATH "..." FORCE)
endif()

# Start with a clean build bundle directory every time.
install(CODE "
  file(REMOVE_RECURSE \"${BUILD_BUNDLE_DIR}/\")
  " COMPONENT Runtime)

set(INSTALL_BUNDLE_DATA_DIR "${CMAKE_INSTALL_PREFIX}/data")
set(INSTALL_BUNDLE_LIB_DIR "${CMAKE_INSTALL_PREFIX}/lib")

install(TARGETS ${BINARY_NAME} RUNTIME DESTINATION "${CMAKE_INSTALL_PREFIX}"
  COMPONENT Runtime)

install(FILES "${FLUTTER_ICU_DATA_FILE}" DESTINATION "${INSTALL_BUNDLE_DATA_DIR}"
  COMPONENT Runtime)

install(FILES "${FLUTTER_LIBRARY}" DESTINATION "${INSTALL_BUNDLE_LIB_DIR}"
  COMPONENT Runtime)

foreach(bundled_library ${PLUGIN_BUNDLED_LIBRARIES})
  install(FILES "${bundled_library}"
    DESTINATION "${INSTALL_BUNDLE_LIB_DIR}"
    COMPONENT Runtime)
endforeach(bundled_library)

# Copy the native assets provided by the build.dart from all packages.
set(NATIVE_ASSETS_DIR "${PROJECT_BUILD_DIR}native_assets/linux/")
install(DIRECTORY "${NATIVE_ASSETS_DIR}"
   DESTINATION "${INSTALL_BUNDLE_LIB_DIR}"
   COMPONENT Runtime)

# Fully re-copy the assets directory on each build to avoid having stale files
# from a previous install.
set(FLUTTER_ASSET_DIR_NAME "flutter_assets")
install(CODE "
  file(REMOVE_RECURSE \"${INSTALL_BUNDLE_DATA_DIR}/${FLUTTER_ASSET_DIR_NAME}\")
  " COMPONENT Runtime)
install(DIRECTORY "${PROJECT_BUILD_DIR}/${FLUTTER_ASSET_DIR_NAME}"
  DESTINATION "${INSTALL_BUNDLE_DATA_DIR}" COMPONENT Runtime)

# Install the AOT library on non-Debug builds only.
if(NOT CMAKE_BUILD_TYPE MATCHES "Debug")
  install(FILES "${AOT_LIBRARY}" DESTINATION "${INSTALL_BUNDLE_LIB_DIR}"
    COMPONENT Runtime)
endif()

</file>
<file path="macos/Flutter/ephemeral/Flutter-Generated.xcconfig">
// This is a generated file; do not edit or check into version control.
FLUTTER_ROOT=/Users/varyable/Workspace/flutter
FLUTTER_APPLICATION_PATH=/Users/varyable/Workspace/mobileapps/foodster
COCOAPODS_PARALLEL_CODE_SIGN=true
FLUTTER_BUILD_DIR=build
FLUTTER_BUILD_NAME=1.0.0
FLUTTER_BUILD_NUMBER=1
DART_OBFUSCATION=false
TRACK_WIDGET_CREATION=true
TREE_SHAKE_ICONS=false
PACKAGE_CONFIG=.dart_tool/package_config.json

</file>
<file path="macos/Flutter/ephemeral/flutter_export_environment.sh">
#!/bin/sh
# This is a generated file; do not edit or check into version control.
export "FLUTTER_ROOT=/Users/varyable/Workspace/flutter"
export "FLUTTER_APPLICATION_PATH=/Users/varyable/Workspace/mobileapps/foodster"
export "COCOAPODS_PARALLEL_CODE_SIGN=true"
export "FLUTTER_BUILD_DIR=build"
export "FLUTTER_BUILD_NAME=1.0.0"
export "FLUTTER_BUILD_NUMBER=1"
export "DART_OBFUSCATION=false"
export "TRACK_WIDGET_CREATION=true"
export "TREE_SHAKE_ICONS=false"
export "PACKAGE_CONFIG=.dart_tool/package_config.json"

</file>
<file path="macos/Flutter/Flutter-Debug.xcconfig">
#include? "Pods/Target Support Files/Pods-Runner/Pods-Runner.debug.xcconfig"
#include "ephemeral/Flutter-Generated.xcconfig"

</file>
<file path="macos/Flutter/Flutter-Release.xcconfig">
#include? "Pods/Target Support Files/Pods-Runner/Pods-Runner.release.xcconfig"
#include "ephemeral/Flutter-Generated.xcconfig"

</file>
<file path="macos/Flutter/GeneratedPluginRegistrant.swift">
//
//  Generated file. Do not edit.
//

import FlutterMacOS
import Foundation

import app_links
import connectivity_plus
import flutter_secure_storage_macos
import path_provider_foundation
import shared_preferences_foundation
import sqflite_darwin
import url_launcher_macos

func RegisterGeneratedPlugins(registry: FlutterPluginRegistry) {
  AppLinksMacosPlugin.register(with: registry.registrar(forPlugin: "AppLinksMacosPlugin"))
  ConnectivityPlugin.register(with: registry.registrar(forPlugin: "ConnectivityPlugin"))
  FlutterSecureStoragePlugin.register(with: registry.registrar(forPlugin: "FlutterSecureStoragePlugin"))
  PathProviderPlugin.register(with: registry.registrar(forPlugin: "PathProviderPlugin"))
  SharedPreferencesPlugin.register(with: registry.registrar(forPlugin: "SharedPreferencesPlugin"))
  SqflitePlugin.register(with: registry.registrar(forPlugin: "SqflitePlugin"))
  UrlLauncherPlugin.register(with: registry.registrar(forPlugin: "UrlLauncherPlugin"))
}

</file>
<file path="macos/Runner/Assets.xcassets/AppIcon.appiconset/Contents.json">
{
  "images" : [
    {
      "size" : "16x16",
      "idiom" : "mac",
      "filename" : "app_icon_16.png",
      "scale" : "1x"
    },
    {
      "size" : "16x16",
      "idiom" : "mac",
      "filename" : "app_icon_32.png",
      "scale" : "2x"
    },
    {
      "size" : "32x32",
      "idiom" : "mac",
      "filename" : "app_icon_32.png",
      "scale" : "1x"
    },
    {
      "size" : "32x32",
      "idiom" : "mac",
      "filename" : "app_icon_64.png",
      "scale" : "2x"
    },
    {
      "size" : "128x128",
      "idiom" : "mac",
      "filename" : "app_icon_128.png",
      "scale" : "1x"
    },
    {
      "size" : "128x128",
      "idiom" : "mac",
      "filename" : "app_icon_256.png",
      "scale" : "2x"
    },
    {
      "size" : "256x256",
      "idiom" : "mac",
      "filename" : "app_icon_256.png",
      "scale" : "1x"
    },
    {
      "size" : "256x256",
      "idiom" : "mac",
      "filename" : "app_icon_512.png",
      "scale" : "2x"
    },
    {
      "size" : "512x512",
      "idiom" : "mac",
      "filename" : "app_icon_512.png",
      "scale" : "1x"
    },
    {
      "size" : "512x512",
      "idiom" : "mac",
      "filename" : "app_icon_1024.png",
      "scale" : "2x"
    }
  ],
  "info" : {
    "version" : 1,
    "author" : "xcode"
  }
}

</file>
<file path="macos/Runner/Base.lproj/MainMenu.xib">
<?xml version="1.0" encoding="UTF-8"?>
<document type="com.apple.InterfaceBuilder3.Cocoa.XIB" version="3.0" toolsVersion="14490.70" targetRuntime="MacOSX.Cocoa" propertyAccessControl="none" useAutolayout="YES" customObjectInstantitationMethod="direct">
    <dependencies>
        <deployment identifier="macosx"/>
        <plugIn identifier="com.apple.InterfaceBuilder.CocoaPlugin" version="14490.70"/>
        <capability name="documents saved in the Xcode 8 format" minToolsVersion="8.0"/>
    </dependencies>
    <objects>
        <customObject id="-2" userLabel="File's Owner" customClass="NSApplication">
            <connections>
                <outlet property="delegate" destination="Voe-Tx-rLC" id="GzC-gU-4Uq"/>
            </connections>
        </customObject>
        <customObject id="-1" userLabel="First Responder" customClass="FirstResponder"/>
        <customObject id="-3" userLabel="Application" customClass="NSObject"/>
        <customObject id="Voe-Tx-rLC" customClass="AppDelegate" customModule="Runner" customModuleProvider="target">
            <connections>
                <outlet property="applicationMenu" destination="uQy-DD-JDr" id="XBo-yE-nKs"/>
                <outlet property="mainFlutterWindow" destination="QvC-M9-y7g" id="gIp-Ho-8D9"/>
            </connections>
        </customObject>
        <customObject id="YLy-65-1bz" customClass="NSFontManager"/>
        <menu title="Main Menu" systemMenu="main" id="AYu-sK-qS6">
            <items>
                <menuItem title="APP_NAME" id="1Xt-HY-uBw">
                    <modifierMask key="keyEquivalentModifierMask"/>
                    <menu key="submenu" title="APP_NAME" systemMenu="apple" id="uQy-DD-JDr">
                        <items>
                            <menuItem title="About APP_NAME" id="5kV-Vb-QxS">
                                <modifierMask key="keyEquivalentModifierMask"/>
                                <connections>
                                    <action selector="orderFrontStandardAboutPanel:" target="-1" id="Exp-CZ-Vem"/>
                                </connections>
                            </menuItem>
                            <menuItem isSeparatorItem="YES" id="VOq-y0-SEH"/>
                            <menuItem title="Preferences…" keyEquivalent="," id="BOF-NM-1cW"/>
                            <menuItem isSeparatorItem="YES" id="wFC-TO-SCJ"/>
                            <menuItem title="Services" id="NMo-om-nkz">
                                <modifierMask key="keyEquivalentModifierMask"/>
                                <menu key="submenu" title="Services" systemMenu="services" id="hz9-B4-Xy5"/>
                            </menuItem>
                            <menuItem isSeparatorItem="YES" id="4je-JR-u6R"/>
                            <menuItem title="Hide APP_NAME" keyEquivalent="h" id="Olw-nP-bQN">
                                <connections>
                                    <action selector="hide:" target="-1" id="PnN-Uc-m68"/>
                                </connections>
                            </menuItem>
                            <menuItem title="Hide Others" keyEquivalent="h" id="Vdr-fp-XzO">
                                <modifierMask key="keyEquivalentModifierMask" option="YES" command="YES"/>
                                <connections>
                                    <action selector="hideOtherApplications:" target="-1" id="VT4-aY-XCT"/>
                                </connections>
                            </menuItem>
                            <menuItem title="Show All" id="Kd2-mp-pUS">
                                <modifierMask key="keyEquivalentModifierMask"/>
                                <connections>
                                    <action selector="unhideAllApplications:" target="-1" id="Dhg-Le-xox"/>
                                </connections>
                            </menuItem>
                            <menuItem isSeparatorItem="YES" id="kCx-OE-vgT"/>
                            <menuItem title="Quit APP_NAME" keyEquivalent="q" id="4sb-4s-VLi">
                                <connections>
                                    <action selector="terminate:" target="-1" id="Te7-pn-YzF"/>
                                </connections>
                            </menuItem>
                        </items>
                    </menu>
                </menuItem>
                <menuItem title="Edit" id="5QF-Oa-p0T">
                    <modifierMask key="keyEquivalentModifierMask"/>
                    <menu key="submenu" title="Edit" id="W48-6f-4Dl">
                        <items>
                            <menuItem title="Undo" keyEquivalent="z" id="dRJ-4n-Yzg">
                                <connections>
                                    <action selector="undo:" target="-1" id="M6e-cu-g7V"/>
                                </connections>
                            </menuItem>
                            <menuItem title="Redo" keyEquivalent="Z" id="6dh-zS-Vam">
                                <connections>
                                    <action selector="redo:" target="-1" id="oIA-Rs-6OD"/>
                                </connections>
                            </menuItem>
                            <menuItem isSeparatorItem="YES" id="WRV-NI-Exz"/>
                            <menuItem title="Cut" keyEquivalent="x" id="uRl-iY-unG">
                                <connections>
                                    <action selector="cut:" target="-1" id="YJe-68-I9s"/>
                                </connections>
                            </menuItem>
                            <menuItem title="Copy" keyEquivalent="c" id="x3v-GG-iWU">
                                <connections>
                                    <action selector="copy:" target="-1" id="G1f-GL-Joy"/>
                                </connections>
                            </menuItem>
                            <menuItem title="Paste" keyEquivalent="v" id="gVA-U4-sdL">
                                <connections>
                                    <action selector="paste:" target="-1" id="UvS-8e-Qdg"/>
                                </connections>
                            </menuItem>
                            <menuItem title="Paste and Match Style" keyEquivalent="V" id="WeT-3V-zwk">
                                <modifierMask key="keyEquivalentModifierMask" option="YES" command="YES"/>
                                <connections>
                                    <action selector="pasteAsPlainText:" target="-1" id="cEh-KX-wJQ"/>
                                </connections>
                            </menuItem>
                            <menuItem title="Delete" id="pa3-QI-u2k">
                                <modifierMask key="keyEquivalentModifierMask"/>
                                <connections>
                                    <action selector="delete:" target="-1" id="0Mk-Ml-PaM"/>
                                </connections>
                            </menuItem>
                            <menuItem title="Select All" keyEquivalent="a" id="Ruw-6m-B2m">
                                <connections>
                                    <action selector="selectAll:" target="-1" id="VNm-Mi-diN"/>
                                </connections>
                            </menuItem>
                            <menuItem isSeparatorItem="YES" id="uyl-h8-XO2"/>
                            <menuItem title="Find" id="4EN-yA-p0u">
                                <modifierMask key="keyEquivalentModifierMask"/>
                                <menu key="submenu" title="Find" id="1b7-l0-nxx">
                                    <items>
                                        <menuItem title="Find…" tag="1" keyEquivalent="f" id="Xz5-n4-O0W">
                                            <connections>
                                                <action selector="performFindPanelAction:" target="-1" id="cD7-Qs-BN4"/>
                                            </connections>
                                        </menuItem>
                                        <menuItem title="Find and Replace…" tag="12" keyEquivalent="f" id="YEy-JH-Tfz">
                                            <modifierMask key="keyEquivalentModifierMask" option="YES" command="YES"/>
                                            <connections>
                                                <action selector="performFindPanelAction:" target="-1" id="WD3-Gg-5AJ"/>
                                            </connections>
                                        </menuItem>
                                        <menuItem title="Find Next" tag="2" keyEquivalent="g" id="q09-fT-Sye">
                                            <connections>
                                                <action selector="performFindPanelAction:" target="-1" id="NDo-RZ-v9R"/>
                                            </connections>
                                        </menuItem>
                                        <menuItem title="Find Previous" tag="3" keyEquivalent="G" id="OwM-mh-QMV">
                                            <connections>
                                                <action selector="performFindPanelAction:" target="-1" id="HOh-sY-3ay"/>
                                            </connections>
                                        </menuItem>
                                        <menuItem title="Use Selection for Find" tag="7" keyEquivalent="e" id="buJ-ug-pKt">
                                            <connections>
                                                <action selector="performFindPanelAction:" target="-1" id="U76-nv-p5D"/>
                                            </connections>
                                        </menuItem>
                                        <menuItem title="Jump to Selection" keyEquivalent="j" id="S0p-oC-mLd">
                                            <connections>
                                                <action selector="centerSelectionInVisibleArea:" target="-1" id="IOG-6D-g5B"/>
                                            </connections>
                                        </menuItem>
                                    </items>
                                </menu>
                            </menuItem>
                            <menuItem title="Spelling and Grammar" id="Dv1-io-Yv7">
                                <modifierMask key="keyEquivalentModifierMask"/>
                                <menu key="submenu" title="Spelling" id="3IN-sU-3Bg">
                                    <items>
                                        <menuItem title="Show Spelling and Grammar" keyEquivalent=":" id="HFo-cy-zxI">
                                            <connections>
                                                <action selector="showGuessPanel:" target="-1" id="vFj-Ks-hy3"/>
                                            </connections>
                                        </menuItem>
                                        <menuItem title="Check Document Now" keyEquivalent=";" id="hz2-CU-CR7">
                                            <connections>
                                                <action selector="checkSpelling:" target="-1" id="fz7-VC-reM"/>
                                            </connections>
                                        </menuItem>
                                        <menuItem isSeparatorItem="YES" id="bNw-od-mp5"/>
                                        <menuItem title="Check Spelling While Typing" id="rbD-Rh-wIN">
                                            <modifierMask key="keyEquivalentModifierMask"/>
                                            <connections>
                                                <action selector="toggleContinuousSpellChecking:" target="-1" id="7w6-Qz-0kB"/>
                                            </connections>
                                        </menuItem>
                                        <menuItem title="Check Grammar With Spelling" id="mK6-2p-4JG">
                                            <modifierMask key="keyEquivalentModifierMask"/>
                                            <connections>
                                                <action selector="toggleGrammarChecking:" target="-1" id="muD-Qn-j4w"/>
                                            </connections>
                                        </menuItem>
                                        <menuItem title="Correct Spelling Automatically" id="78Y-hA-62v">
                                            <modifierMask key="keyEquivalentModifierMask"/>
                                            <connections>
                                                <action selector="toggleAutomaticSpellingCorrection:" target="-1" id="2lM-Qi-WAP"/>
                                            </connections>
                                        </menuItem>
                                    </items>
                                </menu>
                            </menuItem>
                            <menuItem title="Substitutions" id="9ic-FL-obx">
                                <modifierMask key="keyEquivalentModifierMask"/>
                                <menu key="submenu" title="Substitutions" id="FeM-D8-WVr">
                                    <items>
                                        <menuItem title="Show Substitutions" id="z6F-FW-3nz">
                                            <modifierMask key="keyEquivalentModifierMask"/>
                                            <connections>
                                                <action selector="orderFrontSubstitutionsPanel:" target="-1" id="oku-mr-iSq"/>
                                            </connections>
                                        </menuItem>
                                        <menuItem isSeparatorItem="YES" id="gPx-C9-uUO"/>
                                        <menuItem title="Smart Copy/Paste" id="9yt-4B-nSM">
                                            <modifierMask key="keyEquivalentModifierMask"/>
                                            <connections>
                                                <action selector="toggleSmartInsertDelete:" target="-1" id="3IJ-Se-DZD"/>
                                            </connections>
                                        </menuItem>
                                        <menuItem title="Smart Quotes" id="hQb-2v-fYv">
                                            <modifierMask key="keyEquivalentModifierMask"/>
                                            <connections>
                                                <action selector="toggleAutomaticQuoteSubstitution:" target="-1" id="ptq-xd-QOA"/>
                                            </connections>
                                        </menuItem>
                                        <menuItem title="Smart Dashes" id="rgM-f4-ycn">
                                            <modifierMask key="keyEquivalentModifierMask"/>
                                            <connections>
                                                <action selector="toggleAutomaticDashSubstitution:" target="-1" id="oCt-pO-9gS"/>
                                            </connections>
                                        </menuItem>
                                        <menuItem title="Smart Links" id="cwL-P1-jid">
                                            <modifierMask key="keyEquivalentModifierMask"/>
                                            <connections>
                                                <action selector="toggleAutomaticLinkDetection:" target="-1" id="Gip-E3-Fov"/>
                                            </connections>
                                        </menuItem>
                                        <menuItem title="Data Detectors" id="tRr-pd-1PS">
                                            <modifierMask key="keyEquivalentModifierMask"/>
                                            <connections>
                                                <action selector="toggleAutomaticDataDetection:" target="-1" id="R1I-Nq-Kbl"/>
                                            </connections>
                                        </menuItem>
                                        <menuItem title="Text Replacement" id="HFQ-gK-NFA">
                                            <modifierMask key="keyEquivalentModifierMask"/>
                                            <connections>
                                                <action selector="toggleAutomaticTextReplacement:" target="-1" id="DvP-Fe-Py6"/>
                                            </connections>
                                        </menuItem>
                                    </items>
                                </menu>
                            </menuItem>
                            <menuItem title="Transformations" id="2oI-Rn-ZJC">
                                <modifierMask key="keyEquivalentModifierMask"/>
                                <menu key="submenu" title="Transformations" id="c8a-y6-VQd">
                                    <items>
                                        <menuItem title="Make Upper Case" id="vmV-6d-7jI">
                                            <modifierMask key="keyEquivalentModifierMask"/>
                                            <connections>
                                                <action selector="uppercaseWord:" target="-1" id="sPh-Tk-edu"/>
                                            </connections>
                                        </menuItem>
                                        <menuItem title="Make Lower Case" id="d9M-CD-aMd">
                                            <modifierMask key="keyEquivalentModifierMask"/>
                                            <connections>
                                                <action selector="lowercaseWord:" target="-1" id="iUZ-b5-hil"/>
                                            </connections>
                                        </menuItem>
                                        <menuItem title="Capitalize" id="UEZ-Bs-lqG">
                                            <modifierMask key="keyEquivalentModifierMask"/>
                                            <connections>
                                                <action selector="capitalizeWord:" target="-1" id="26H-TL-nsh"/>
                                            </connections>
                                        </menuItem>
                                    </items>
                                </menu>
                            </menuItem>
                            <menuItem title="Speech" id="xrE-MZ-jX0">
                                <modifierMask key="keyEquivalentModifierMask"/>
                                <menu key="submenu" title="Speech" id="3rS-ZA-NoH">
                                    <items>
                                        <menuItem title="Start Speaking" id="Ynk-f8-cLZ">
                                            <modifierMask key="keyEquivalentModifierMask"/>
                                            <connections>
                                                <action selector="startSpeaking:" target="-1" id="654-Ng-kyl"/>
                                            </connections>
                                        </menuItem>
                                        <menuItem title="Stop Speaking" id="Oyz-dy-DGm">
                                            <modifierMask key="keyEquivalentModifierMask"/>
                                            <connections>
                                                <action selector="stopSpeaking:" target="-1" id="dX8-6p-jy9"/>
                                            </connections>
                                        </menuItem>
                                    </items>
                                </menu>
                            </menuItem>
                        </items>
                    </menu>
                </menuItem>
                <menuItem title="View" id="H8h-7b-M4v">
                    <modifierMask key="keyEquivalentModifierMask"/>
                    <menu key="submenu" title="View" id="HyV-fh-RgO">
                        <items>
                            <menuItem title="Enter Full Screen" keyEquivalent="f" id="4J7-dP-txa">
                                <modifierMask key="keyEquivalentModifierMask" control="YES" command="YES"/>
                                <connections>
                                    <action selector="toggleFullScreen:" target="-1" id="dU3-MA-1Rq"/>
                                </connections>
                            </menuItem>
                        </items>
                    </menu>
                </menuItem>
                <menuItem title="Window" id="aUF-d1-5bR">
                    <modifierMask key="keyEquivalentModifierMask"/>
                    <menu key="submenu" title="Window" systemMenu="window" id="Td7-aD-5lo">
                        <items>
                            <menuItem title="Minimize" keyEquivalent="m" id="OY7-WF-poV">
                                <connections>
                                    <action selector="performMiniaturize:" target="-1" id="VwT-WD-YPe"/>
                                </connections>
                            </menuItem>
                            <menuItem title="Zoom" id="R4o-n2-Eq4">
                                <modifierMask key="keyEquivalentModifierMask"/>
                                <connections>
                                    <action selector="performZoom:" target="-1" id="DIl-cC-cCs"/>
                                </connections>
                            </menuItem>
                            <menuItem isSeparatorItem="YES" id="eu3-7i-yIM"/>
                            <menuItem title="Bring All to Front" id="LE2-aR-0XJ">
                                <modifierMask key="keyEquivalentModifierMask"/>
                                <connections>
                                    <action selector="arrangeInFront:" target="-1" id="DRN-fu-gQh"/>
                                </connections>
                            </menuItem>
                        </items>
                    </menu>
                </menuItem>
                <menuItem title="Help" id="EPT-qC-fAb">
                    <modifierMask key="keyEquivalentModifierMask"/>
                    <menu key="submenu" title="Help" systemMenu="help" id="rJ0-wn-3NY"/>
                </menuItem>
            </items>
            <point key="canvasLocation" x="142" y="-258"/>
        </menu>
        <window title="APP_NAME" allowsToolTipsWhenApplicationIsInactive="NO" autorecalculatesKeyViewLoop="NO" releasedWhenClosed="NO" animationBehavior="default" id="QvC-M9-y7g" customClass="MainFlutterWindow" customModule="Runner" customModuleProvider="target">
            <windowStyleMask key="styleMask" titled="YES" closable="YES" miniaturizable="YES" resizable="YES"/>
            <rect key="contentRect" x="335" y="390" width="800" height="600"/>
            <rect key="screenRect" x="0.0" y="0.0" width="2560" height="1577"/>
            <view key="contentView" wantsLayer="YES" id="EiT-Mj-1SZ">
                <rect key="frame" x="0.0" y="0.0" width="800" height="600"/>
                <autoresizingMask key="autoresizingMask"/>
            </view>
        </window>
    </objects>
</document>

</file>
<file path="macos/Runner/Configs/AppInfo.xcconfig">
// Application-level settings for the Runner target.
//
// This may be replaced with something auto-generated from metadata (e.g., pubspec.yaml) in the
// future. If not, the values below would default to using the project name when this becomes a
// 'flutter create' template.

// The application's name. By default this is also the title of the Flutter window.
PRODUCT_NAME = foodster

// The application's bundle identifier
PRODUCT_BUNDLE_IDENTIFIER = com.example.foodster

// The copyright displayed in application information
PRODUCT_COPYRIGHT = Copyright © 2025 com.example. All rights reserved.

</file>
<file path="macos/Runner/Configs/Debug.xcconfig">
#include "../../Flutter/Flutter-Debug.xcconfig"
#include "Warnings.xcconfig"

</file>
<file path="macos/Runner/Configs/Release.xcconfig">
#include "../../Flutter/Flutter-Release.xcconfig"
#include "Warnings.xcconfig"

</file>
<file path="macos/Runner/Configs/Warnings.xcconfig">
WARNING_CFLAGS = -Wall -Wconditional-uninitialized -Wnullable-to-nonnull-conversion -Wmissing-method-return-type -Woverlength-strings
GCC_WARN_UNDECLARED_SELECTOR = YES
CLANG_UNDEFINED_BEHAVIOR_SANITIZER_NULLABILITY = YES
CLANG_WARN_UNGUARDED_AVAILABILITY = YES_AGGRESSIVE
CLANG_WARN__DUPLICATE_METHOD_MATCH = YES
CLANG_WARN_PRAGMA_PACK = YES
CLANG_WARN_STRICT_PROTOTYPES = YES
CLANG_WARN_COMMA = YES
GCC_WARN_STRICT_SELECTOR_MATCH = YES
CLANG_WARN_OBJC_REPEATED_USE_OF_WEAK = YES
CLANG_WARN_OBJC_IMPLICIT_RETAIN_SELF = YES
GCC_WARN_SHADOW = YES
CLANG_WARN_UNREACHABLE_CODE = YES

</file>
<file path="macos/Runner/AppDelegate.swift">
import Cocoa
import FlutterMacOS

@main
class AppDelegate: FlutterAppDelegate {
  override func applicationShouldTerminateAfterLastWindowClosed(_ sender: NSApplication) -> Bool {
    return true
  }

  override func applicationSupportsSecureRestorableState(_ app: NSApplication) -> Bool {
    return true
  }
}

</file>
<file path="macos/Runner/DebugProfile.entitlements">
<?xml version="1.0" encoding="UTF-8"?>
<!DOCTYPE plist PUBLIC "-//Apple//DTD PLIST 1.0//EN" "http://www.apple.com/DTDs/PropertyList-1.0.dtd">
<plist version="1.0">
<dict>
	<key>com.apple.security.app-sandbox</key>
	<true/>
	<key>com.apple.security.cs.allow-jit</key>
	<true/>
	<key>com.apple.security.network.server</key>
	<true/>
</dict>
</plist>

</file>
<file path="macos/Runner/Info.plist">
<?xml version="1.0" encoding="UTF-8"?>
<!DOCTYPE plist PUBLIC "-//Apple//DTD PLIST 1.0//EN" "http://www.apple.com/DTDs/PropertyList-1.0.dtd">
<plist version="1.0">
<dict>
	<key>CFBundleDevelopmentRegion</key>
	<string>$(DEVELOPMENT_LANGUAGE)</string>
	<key>CFBundleExecutable</key>
	<string>$(EXECUTABLE_NAME)</string>
	<key>CFBundleIconFile</key>
	<string></string>
	<key>CFBundleIdentifier</key>
	<string>$(PRODUCT_BUNDLE_IDENTIFIER)</string>
	<key>CFBundleInfoDictionaryVersion</key>
	<string>6.0</string>
	<key>CFBundleName</key>
	<string>$(PRODUCT_NAME)</string>
	<key>CFBundlePackageType</key>
	<string>APPL</string>
	<key>CFBundleShortVersionString</key>
	<string>$(FLUTTER_BUILD_NAME)</string>
	<key>CFBundleVersion</key>
	<string>$(FLUTTER_BUILD_NUMBER)</string>
	<key>LSMinimumSystemVersion</key>
	<string>$(MACOSX_DEPLOYMENT_TARGET)</string>
	<key>NSHumanReadableCopyright</key>
	<string>$(PRODUCT_COPYRIGHT)</string>
	<key>NSMainNibFile</key>
	<string>MainMenu</string>
	<key>NSPrincipalClass</key>
	<string>NSApplication</string>
</dict>
</plist>

</file>
<file path="macos/Runner/MainFlutterWindow.swift">
import Cocoa
import FlutterMacOS

class MainFlutterWindow: NSWindow {
  override func awakeFromNib() {
    let flutterViewController = FlutterViewController()
    let windowFrame = self.frame
    self.contentViewController = flutterViewController
    self.setFrame(windowFrame, display: true)

    RegisterGeneratedPlugins(registry: flutterViewController)

    super.awakeFromNib()
  }
}

</file>
<file path="macos/Runner/Release.entitlements">
<?xml version="1.0" encoding="UTF-8"?>
<!DOCTYPE plist PUBLIC "-//Apple//DTD PLIST 1.0//EN" "http://www.apple.com/DTDs/PropertyList-1.0.dtd">
<plist version="1.0">
<dict>
	<key>com.apple.security.app-sandbox</key>
	<true/>
</dict>
</plist>

</file>
<file path="macos/Runner.xcodeproj/project.xcworkspace/xcshareddata/IDEWorkspaceChecks.plist">
<?xml version="1.0" encoding="UTF-8"?>
<!DOCTYPE plist PUBLIC "-//Apple//DTD PLIST 1.0//EN" "http://www.apple.com/DTDs/PropertyList-1.0.dtd">
<plist version="1.0">
<dict>
	<key>IDEDidComputeMac32BitWarning</key>
	<true/>
</dict>
</plist>

</file>
<file path="macos/Runner.xcodeproj/xcshareddata/xcschemes/Runner.xcscheme">
<?xml version="1.0" encoding="UTF-8"?>
<Scheme
   LastUpgradeVersion = "1510"
   version = "1.3">
   <BuildAction
      parallelizeBuildables = "YES"
      buildImplicitDependencies = "YES">
      <BuildActionEntries>
         <BuildActionEntry
            buildForTesting = "YES"
            buildForRunning = "YES"
            buildForProfiling = "YES"
            buildForArchiving = "YES"
            buildForAnalyzing = "YES">
            <BuildableReference
               BuildableIdentifier = "primary"
               BlueprintIdentifier = "33CC10EC2044A3C60003C045"
               BuildableName = "foodster.app"
               BlueprintName = "Runner"
               ReferencedContainer = "container:Runner.xcodeproj">
            </BuildableReference>
         </BuildActionEntry>
      </BuildActionEntries>
   </BuildAction>
   <TestAction
      buildConfiguration = "Debug"
      selectedDebuggerIdentifier = "Xcode.DebuggerFoundation.Debugger.LLDB"
      selectedLauncherIdentifier = "Xcode.DebuggerFoundation.Launcher.LLDB"
      shouldUseLaunchSchemeArgsEnv = "YES">
      <MacroExpansion>
         <BuildableReference
            BuildableIdentifier = "primary"
            BlueprintIdentifier = "33CC10EC2044A3C60003C045"
            BuildableName = "foodster.app"
            BlueprintName = "Runner"
            ReferencedContainer = "container:Runner.xcodeproj">
         </BuildableReference>
      </MacroExpansion>
      <Testables>
         <TestableReference
            skipped = "NO"
            parallelizable = "YES">
            <BuildableReference
               BuildableIdentifier = "primary"
               BlueprintIdentifier = "331C80D4294CF70F00263BE5"
               BuildableName = "RunnerTests.xctest"
               BlueprintName = "RunnerTests"
               ReferencedContainer = "container:Runner.xcodeproj">
            </BuildableReference>
         </TestableReference>
      </Testables>
   </TestAction>
   <LaunchAction
      buildConfiguration = "Debug"
      selectedDebuggerIdentifier = "Xcode.DebuggerFoundation.Debugger.LLDB"
      selectedLauncherIdentifier = "Xcode.DebuggerFoundation.Launcher.LLDB"
      launchStyle = "0"
      useCustomWorkingDirectory = "NO"
      ignoresPersistentStateOnLaunch = "NO"
      debugDocumentVersioning = "YES"
      debugServiceExtension = "internal"
      enableGPUValidationMode = "1"
      allowLocationSimulation = "YES">
      <BuildableProductRunnable
         runnableDebuggingMode = "0">
         <BuildableReference
            BuildableIdentifier = "primary"
            BlueprintIdentifier = "33CC10EC2044A3C60003C045"
            BuildableName = "foodster.app"
            BlueprintName = "Runner"
            ReferencedContainer = "container:Runner.xcodeproj">
         </BuildableReference>
      </BuildableProductRunnable>
   </LaunchAction>
   <ProfileAction
      buildConfiguration = "Profile"
      shouldUseLaunchSchemeArgsEnv = "YES"
      savedToolIdentifier = ""
      useCustomWorkingDirectory = "NO"
      debugDocumentVersioning = "YES">
      <BuildableProductRunnable
         runnableDebuggingMode = "0">
         <BuildableReference
            BuildableIdentifier = "primary"
            BlueprintIdentifier = "33CC10EC2044A3C60003C045"
            BuildableName = "foodster.app"
            BlueprintName = "Runner"
            ReferencedContainer = "container:Runner.xcodeproj">
         </BuildableReference>
      </BuildableProductRunnable>
   </ProfileAction>
   <AnalyzeAction
      buildConfiguration = "Debug">
   </AnalyzeAction>
   <ArchiveAction
      buildConfiguration = "Release"
      revealArchiveInOrganizer = "YES">
   </ArchiveAction>
</Scheme>

</file>
<file path="macos/Runner.xcodeproj/project.pbxproj">
// !$*UTF8*$!
{
	archiveVersion = 1;
	classes = {
	};
	objectVersion = 54;
	objects = {

/* Begin PBXAggregateTarget section */
		33CC111A2044C6BA0003C045 /* Flutter Assemble */ = {
			isa = PBXAggregateTarget;
			buildConfigurationList = 33CC111B2044C6BA0003C045 /* Build configuration list for PBXAggregateTarget "Flutter Assemble" */;
			buildPhases = (
				33CC111E2044C6BF0003C045 /* ShellScript */,
			);
			dependencies = (
			);
			name = "Flutter Assemble";
			productName = FLX;
		};
/* End PBXAggregateTarget section */

/* Begin PBXBuildFile section */
		331C80D8294CF71000263BE5 /* RunnerTests.swift in Sources */ = {isa = PBXBuildFile; fileRef = 331C80D7294CF71000263BE5 /* RunnerTests.swift */; };
		335BBD1B22A9A15E00E9071D /* GeneratedPluginRegistrant.swift in Sources */ = {isa = PBXBuildFile; fileRef = 335BBD1A22A9A15E00E9071D /* GeneratedPluginRegistrant.swift */; };
		33CC10F12044A3C60003C045 /* AppDelegate.swift in Sources */ = {isa = PBXBuildFile; fileRef = 33CC10F02044A3C60003C045 /* AppDelegate.swift */; };
		33CC10F32044A3C60003C045 /* Assets.xcassets in Resources */ = {isa = PBXBuildFile; fileRef = 33CC10F22044A3C60003C045 /* Assets.xcassets */; };
		33CC10F62044A3C60003C045 /* MainMenu.xib in Resources */ = {isa = PBXBuildFile; fileRef = 33CC10F42044A3C60003C045 /* MainMenu.xib */; };
		33CC11132044BFA00003C045 /* MainFlutterWindow.swift in Sources */ = {isa = PBXBuildFile; fileRef = 33CC11122044BFA00003C045 /* MainFlutterWindow.swift */; };
/* End PBXBuildFile section */

/* Begin PBXContainerItemProxy section */
		331C80D9294CF71000263BE5 /* PBXContainerItemProxy */ = {
			isa = PBXContainerItemProxy;
			containerPortal = 33CC10E52044A3C60003C045 /* Project object */;
			proxyType = 1;
			remoteGlobalIDString = 33CC10EC2044A3C60003C045;
			remoteInfo = Runner;
		};
		33CC111F2044C79F0003C045 /* PBXContainerItemProxy */ = {
			isa = PBXContainerItemProxy;
			containerPortal = 33CC10E52044A3C60003C045 /* Project object */;
			proxyType = 1;
			remoteGlobalIDString = 33CC111A2044C6BA0003C045;
			remoteInfo = FLX;
		};
/* End PBXContainerItemProxy section */

/* Begin PBXCopyFilesBuildPhase section */
		33CC110E2044A8840003C045 /* Bundle Framework */ = {
			isa = PBXCopyFilesBuildPhase;
			buildActionMask = 2147483647;
			dstPath = "";
			dstSubfolderSpec = 10;
			files = (
			);
			name = "Bundle Framework";
			runOnlyForDeploymentPostprocessing = 0;
		};
/* End PBXCopyFilesBuildPhase section */

/* Begin PBXFileReference section */
		331C80D5294CF71000263BE5 /* RunnerTests.xctest */ = {isa = PBXFileReference; explicitFileType = wrapper.cfbundle; includeInIndex = 0; path = RunnerTests.xctest; sourceTree = BUILT_PRODUCTS_DIR; };
		331C80D7294CF71000263BE5 /* RunnerTests.swift */ = {isa = PBXFileReference; lastKnownFileType = sourcecode.swift; path = RunnerTests.swift; sourceTree = "<group>"; };
		333000ED22D3DE5D00554162 /* Warnings.xcconfig */ = {isa = PBXFileReference; lastKnownFileType = text.xcconfig; path = Warnings.xcconfig; sourceTree = "<group>"; };
		335BBD1A22A9A15E00E9071D /* GeneratedPluginRegistrant.swift */ = {isa = PBXFileReference; fileEncoding = 4; lastKnownFileType = sourcecode.swift; path = GeneratedPluginRegistrant.swift; sourceTree = "<group>"; };
		33CC10ED2044A3C60003C045 /* foodster.app */ = {isa = PBXFileReference; explicitFileType = wrapper.application; includeInIndex = 0; path = "foodster.app"; sourceTree = BUILT_PRODUCTS_DIR; };
		33CC10F02044A3C60003C045 /* AppDelegate.swift */ = {isa = PBXFileReference; lastKnownFileType = sourcecode.swift; path = AppDelegate.swift; sourceTree = "<group>"; };
		33CC10F22044A3C60003C045 /* Assets.xcassets */ = {isa = PBXFileReference; lastKnownFileType = folder.assetcatalog; name = Assets.xcassets; path = Runner/Assets.xcassets; sourceTree = "<group>"; };
		33CC10F52044A3C60003C045 /* Base */ = {isa = PBXFileReference; lastKnownFileType = file.xib; name = Base; path = Base.lproj/MainMenu.xib; sourceTree = "<group>"; };
		33CC10F72044A3C60003C045 /* Info.plist */ = {isa = PBXFileReference; lastKnownFileType = text.plist.xml; name = Info.plist; path = Runner/Info.plist; sourceTree = "<group>"; };
		33CC11122044BFA00003C045 /* MainFlutterWindow.swift */ = {isa = PBXFileReference; lastKnownFileType = sourcecode.swift; path = MainFlutterWindow.swift; sourceTree = "<group>"; };
		33CEB47222A05771004F2AC0 /* Flutter-Debug.xcconfig */ = {isa = PBXFileReference; lastKnownFileType = text.xcconfig; path = "Flutter-Debug.xcconfig"; sourceTree = "<group>"; };
		33CEB47422A05771004F2AC0 /* Flutter-Release.xcconfig */ = {isa = PBXFileReference; lastKnownFileType = text.xcconfig; path = "Flutter-Release.xcconfig"; sourceTree = "<group>"; };
		33CEB47722A0578A004F2AC0 /* Flutter-Generated.xcconfig */ = {isa = PBXFileReference; lastKnownFileType = text.xcconfig; name = "Flutter-Generated.xcconfig"; path = "ephemeral/Flutter-Generated.xcconfig"; sourceTree = "<group>"; };
		33E51913231747F40026EE4D /* DebugProfile.entitlements */ = {isa = PBXFileReference; lastKnownFileType = text.plist.entitlements; path = DebugProfile.entitlements; sourceTree = "<group>"; };
		33E51914231749380026EE4D /* Release.entitlements */ = {isa = PBXFileReference; fileEncoding = 4; lastKnownFileType = text.plist.entitlements; path = Release.entitlements; sourceTree = "<group>"; };
		33E5194F232828860026EE4D /* AppInfo.xcconfig */ = {isa = PBXFileReference; lastKnownFileType = text.xcconfig; path = AppInfo.xcconfig; sourceTree = "<group>"; };
		7AFA3C8E1D35360C0083082E /* Release.xcconfig */ = {isa = PBXFileReference; lastKnownFileType = text.xcconfig; path = Release.xcconfig; sourceTree = "<group>"; };
		9740EEB21CF90195004384FC /* Debug.xcconfig */ = {isa = PBXFileReference; fileEncoding = 4; lastKnownFileType = text.xcconfig; path = Debug.xcconfig; sourceTree = "<group>"; };
/* End PBXFileReference section */

/* Begin PBXFrameworksBuildPhase section */
		331C80D2294CF70F00263BE5 /* Frameworks */ = {
			isa = PBXFrameworksBuildPhase;
			buildActionMask = 2147483647;
			files = (
			);
			runOnlyForDeploymentPostprocessing = 0;
		};
		33CC10EA2044A3C60003C045 /* Frameworks */ = {
			isa = PBXFrameworksBuildPhase;
			buildActionMask = 2147483647;
			files = (
			);
			runOnlyForDeploymentPostprocessing = 0;
		};
/* End PBXFrameworksBuildPhase section */

/* Begin PBXGroup section */
		331C80D6294CF71000263BE5 /* RunnerTests */ = {
			isa = PBXGroup;
			children = (
				331C80D7294CF71000263BE5 /* RunnerTests.swift */,
			);
			path = RunnerTests;
			sourceTree = "<group>";
		};
		33BA886A226E78AF003329D5 /* Configs */ = {
			isa = PBXGroup;
			children = (
				33E5194F232828860026EE4D /* AppInfo.xcconfig */,
				9740EEB21CF90195004384FC /* Debug.xcconfig */,
				7AFA3C8E1D35360C0083082E /* Release.xcconfig */,
				333000ED22D3DE5D00554162 /* Warnings.xcconfig */,
			);
			path = Configs;
			sourceTree = "<group>";
		};
		33CC10E42044A3C60003C045 = {
			isa = PBXGroup;
			children = (
				33FAB671232836740065AC1E /* Runner */,
				33CEB47122A05771004F2AC0 /* Flutter */,
				331C80D6294CF71000263BE5 /* RunnerTests */,
				33CC10EE2044A3C60003C045 /* Products */,
				D73912EC22F37F3D000D13A0 /* Frameworks */,
			);
			sourceTree = "<group>";
		};
		33CC10EE2044A3C60003C045 /* Products */ = {
			isa = PBXGroup;
			children = (
				33CC10ED2044A3C60003C045 /* foodster.app */,
				331C80D5294CF71000263BE5 /* RunnerTests.xctest */,
			);
			name = Products;
			sourceTree = "<group>";
		};
		33CC11242044D66E0003C045 /* Resources */ = {
			isa = PBXGroup;
			children = (
				33CC10F22044A3C60003C045 /* Assets.xcassets */,
				33CC10F42044A3C60003C045 /* MainMenu.xib */,
				33CC10F72044A3C60003C045 /* Info.plist */,
			);
			name = Resources;
			path = ..;
			sourceTree = "<group>";
		};
		33CEB47122A05771004F2AC0 /* Flutter */ = {
			isa = PBXGroup;
			children = (
				335BBD1A22A9A15E00E9071D /* GeneratedPluginRegistrant.swift */,
				33CEB47222A05771004F2AC0 /* Flutter-Debug.xcconfig */,
				33CEB47422A05771004F2AC0 /* Flutter-Release.xcconfig */,
				33CEB47722A0578A004F2AC0 /* Flutter-Generated.xcconfig */,
			);
			path = Flutter;
			sourceTree = "<group>";
		};
		33FAB671232836740065AC1E /* Runner */ = {
			isa = PBXGroup;
			children = (
				33CC10F02044A3C60003C045 /* AppDelegate.swift */,
				33CC11122044BFA00003C045 /* MainFlutterWindow.swift */,
				33E51913231747F40026EE4D /* DebugProfile.entitlements */,
				33E51914231749380026EE4D /* Release.entitlements */,
				33CC11242044D66E0003C045 /* Resources */,
				33BA886A226E78AF003329D5 /* Configs */,
			);
			path = Runner;
			sourceTree = "<group>";
		};
		D73912EC22F37F3D000D13A0 /* Frameworks */ = {
			isa = PBXGroup;
			children = (
			);
			name = Frameworks;
			sourceTree = "<group>";
		};
/* End PBXGroup section */

/* Begin PBXNativeTarget section */
		331C80D4294CF70F00263BE5 /* RunnerTests */ = {
			isa = PBXNativeTarget;
			buildConfigurationList = 331C80DE294CF71000263BE5 /* Build configuration list for PBXNativeTarget "RunnerTests" */;
			buildPhases = (
				331C80D1294CF70F00263BE5 /* Sources */,
				331C80D2294CF70F00263BE5 /* Frameworks */,
				331C80D3294CF70F00263BE5 /* Resources */,
			);
			buildRules = (
			);
			dependencies = (
				331C80DA294CF71000263BE5 /* PBXTargetDependency */,
			);
			name = RunnerTests;
			productName = RunnerTests;
			productReference = 331C80D5294CF71000263BE5 /* RunnerTests.xctest */;
			productType = "com.apple.product-type.bundle.unit-test";
		};
		33CC10EC2044A3C60003C045 /* Runner */ = {
			isa = PBXNativeTarget;
			buildConfigurationList = 33CC10FB2044A3C60003C045 /* Build configuration list for PBXNativeTarget "Runner" */;
			buildPhases = (
				33CC10E92044A3C60003C045 /* Sources */,
				33CC10EA2044A3C60003C045 /* Frameworks */,
				33CC10EB2044A3C60003C045 /* Resources */,
				33CC110E2044A8840003C045 /* Bundle Framework */,
				3399D490228B24CF009A79C7 /* ShellScript */,
			);
			buildRules = (
			);
			dependencies = (
				33CC11202044C79F0003C045 /* PBXTargetDependency */,
			);
			name = Runner;
			productName = Runner;
			productReference = 33CC10ED2044A3C60003C045 /* foodster.app */;
			productType = "com.apple.product-type.application";
		};
/* End PBXNativeTarget section */

/* Begin PBXProject section */
		33CC10E52044A3C60003C045 /* Project object */ = {
			isa = PBXProject;
			attributes = {
				BuildIndependentTargetsInParallel = YES;
				LastSwiftUpdateCheck = 0920;
				LastUpgradeCheck = 1510;
				ORGANIZATIONNAME = "";
				TargetAttributes = {
					331C80D4294CF70F00263BE5 = {
						CreatedOnToolsVersion = 14.0;
						TestTargetID = 33CC10EC2044A3C60003C045;
					};
					33CC10EC2044A3C60003C045 = {
						CreatedOnToolsVersion = 9.2;
						LastSwiftMigration = 1100;
						ProvisioningStyle = Automatic;
						SystemCapabilities = {
							com.apple.Sandbox = {
								enabled = 1;
							};
						};
					};
					33CC111A2044C6BA0003C045 = {
						CreatedOnToolsVersion = 9.2;
						ProvisioningStyle = Manual;
					};
				};
			};
			buildConfigurationList = 33CC10E82044A3C60003C045 /* Build configuration list for PBXProject "Runner" */;
			compatibilityVersion = "Xcode 9.3";
			developmentRegion = en;
			hasScannedForEncodings = 0;
			knownRegions = (
				en,
				Base,
			);
			mainGroup = 33CC10E42044A3C60003C045;
			productRefGroup = 33CC10EE2044A3C60003C045 /* Products */;
			projectDirPath = "";
			projectRoot = "";
			targets = (
				33CC10EC2044A3C60003C045 /* Runner */,
				331C80D4294CF70F00263BE5 /* RunnerTests */,
				33CC111A2044C6BA0003C045 /* Flutter Assemble */,
			);
		};
/* End PBXProject section */

/* Begin PBXResourcesBuildPhase section */
		331C80D3294CF70F00263BE5 /* Resources */ = {
			isa = PBXResourcesBuildPhase;
			buildActionMask = 2147483647;
			files = (
			);
			runOnlyForDeploymentPostprocessing = 0;
		};
		33CC10EB2044A3C60003C045 /* Resources */ = {
			isa = PBXResourcesBuildPhase;
			buildActionMask = 2147483647;
			files = (
				33CC10F32044A3C60003C045 /* Assets.xcassets in Resources */,
				33CC10F62044A3C60003C045 /* MainMenu.xib in Resources */,
			);
			runOnlyForDeploymentPostprocessing = 0;
		};
/* End PBXResourcesBuildPhase section */

/* Begin PBXShellScriptBuildPhase section */
		3399D490228B24CF009A79C7 /* ShellScript */ = {
			isa = PBXShellScriptBuildPhase;
			alwaysOutOfDate = 1;
			buildActionMask = 2147483647;
			files = (
			);
			inputFileListPaths = (
			);
			inputPaths = (
			);
			outputFileListPaths = (
			);
			outputPaths = (
			);
			runOnlyForDeploymentPostprocessing = 0;
			shellPath = /bin/sh;
			shellScript = "echo \"$PRODUCT_NAME.app\" > \"$PROJECT_DIR\"/Flutter/ephemeral/.app_filename && \"$FLUTTER_ROOT\"/packages/flutter_tools/bin/macos_assemble.sh embed\n";
		};
		33CC111E2044C6BF0003C045 /* ShellScript */ = {
			isa = PBXShellScriptBuildPhase;
			buildActionMask = 2147483647;
			files = (
			);
			inputFileListPaths = (
				Flutter/ephemeral/FlutterInputs.xcfilelist,
			);
			inputPaths = (
				Flutter/ephemeral/tripwire,
			);
			outputFileListPaths = (
				Flutter/ephemeral/FlutterOutputs.xcfilelist,
			);
			outputPaths = (
			);
			runOnlyForDeploymentPostprocessing = 0;
			shellPath = /bin/sh;
			shellScript = "\"$FLUTTER_ROOT\"/packages/flutter_tools/bin/macos_assemble.sh && touch Flutter/ephemeral/tripwire";
		};
/* End PBXShellScriptBuildPhase section */

/* Begin PBXSourcesBuildPhase section */
		331C80D1294CF70F00263BE5 /* Sources */ = {
			isa = PBXSourcesBuildPhase;
			buildActionMask = 2147483647;
			files = (
				331C80D8294CF71000263BE5 /* RunnerTests.swift in Sources */,
			);
			runOnlyForDeploymentPostprocessing = 0;
		};
		33CC10E92044A3C60003C045 /* Sources */ = {
			isa = PBXSourcesBuildPhase;
			buildActionMask = 2147483647;
			files = (
				33CC11132044BFA00003C045 /* MainFlutterWindow.swift in Sources */,
				33CC10F12044A3C60003C045 /* AppDelegate.swift in Sources */,
				335BBD1B22A9A15E00E9071D /* GeneratedPluginRegistrant.swift in Sources */,
			);
			runOnlyForDeploymentPostprocessing = 0;
		};
/* End PBXSourcesBuildPhase section */

/* Begin PBXTargetDependency section */
		331C80DA294CF71000263BE5 /* PBXTargetDependency */ = {
			isa = PBXTargetDependency;
			target = 33CC10EC2044A3C60003C045 /* Runner */;
			targetProxy = 331C80D9294CF71000263BE5 /* PBXContainerItemProxy */;
		};
		33CC11202044C79F0003C045 /* PBXTargetDependency */ = {
			isa = PBXTargetDependency;
			target = 33CC111A2044C6BA0003C045 /* Flutter Assemble */;
			targetProxy = 33CC111F2044C79F0003C045 /* PBXContainerItemProxy */;
		};
/* End PBXTargetDependency section */

/* Begin PBXVariantGroup section */
		33CC10F42044A3C60003C045 /* MainMenu.xib */ = {
			isa = PBXVariantGroup;
			children = (
				33CC10F52044A3C60003C045 /* Base */,
			);
			name = MainMenu.xib;
			path = Runner;
			sourceTree = "<group>";
		};
/* End PBXVariantGroup section */

/* Begin XCBuildConfiguration section */
		331C80DB294CF71000263BE5 /* Debug */ = {
			isa = XCBuildConfiguration;
			buildSettings = {
				BUNDLE_LOADER = "$(TEST_HOST)";
				CURRENT_PROJECT_VERSION = 1;
				GENERATE_INFOPLIST_FILE = YES;
				MARKETING_VERSION = 1.0;
				PRODUCT_BUNDLE_IDENTIFIER = com.example.foodster.RunnerTests;
				PRODUCT_NAME = "$(TARGET_NAME)";
				SWIFT_VERSION = 5.0;
				TEST_HOST = "$(BUILT_PRODUCTS_DIR)/foodster.app/$(BUNDLE_EXECUTABLE_FOLDER_PATH)/foodster";
			};
			name = Debug;
		};
		331C80DC294CF71000263BE5 /* Release */ = {
			isa = XCBuildConfiguration;
			buildSettings = {
				BUNDLE_LOADER = "$(TEST_HOST)";
				CURRENT_PROJECT_VERSION = 1;
				GENERATE_INFOPLIST_FILE = YES;
				MARKETING_VERSION = 1.0;
				PRODUCT_BUNDLE_IDENTIFIER = com.example.foodster.RunnerTests;
				PRODUCT_NAME = "$(TARGET_NAME)";
				SWIFT_VERSION = 5.0;
				TEST_HOST = "$(BUILT_PRODUCTS_DIR)/foodster.app/$(BUNDLE_EXECUTABLE_FOLDER_PATH)/foodster";
			};
			name = Release;
		};
		331C80DD294CF71000263BE5 /* Profile */ = {
			isa = XCBuildConfiguration;
			buildSettings = {
				BUNDLE_LOADER = "$(TEST_HOST)";
				CURRENT_PROJECT_VERSION = 1;
				GENERATE_INFOPLIST_FILE = YES;
				MARKETING_VERSION = 1.0;
				PRODUCT_BUNDLE_IDENTIFIER = com.example.foodster.RunnerTests;
				PRODUCT_NAME = "$(TARGET_NAME)";
				SWIFT_VERSION = 5.0;
				TEST_HOST = "$(BUILT_PRODUCTS_DIR)/foodster.app/$(BUNDLE_EXECUTABLE_FOLDER_PATH)/foodster";
			};
			name = Profile;
		};
		338D0CE9231458BD00FA5F75 /* Profile */ = {
			isa = XCBuildConfiguration;
			baseConfigurationReference = 7AFA3C8E1D35360C0083082E /* Release.xcconfig */;
			buildSettings = {
				ALWAYS_SEARCH_USER_PATHS = NO;
				ASSETCATALOG_COMPILER_GENERATE_SWIFT_ASSET_SYMBOL_EXTENSIONS = YES;
				CLANG_ANALYZER_NONNULL = YES;
				CLANG_ANALYZER_NUMBER_OBJECT_CONVERSION = YES_AGGRESSIVE;
				CLANG_CXX_LANGUAGE_STANDARD = "gnu++14";
				CLANG_CXX_LIBRARY = "libc++";
				CLANG_ENABLE_MODULES = YES;
				CLANG_ENABLE_OBJC_ARC = YES;
				CLANG_WARN_BLOCK_CAPTURE_AUTORELEASING = YES;
				CLANG_WARN_BOOL_CONVERSION = YES;
				CLANG_WARN_CONSTANT_CONVERSION = YES;
				CLANG_WARN_DEPRECATED_OBJC_IMPLEMENTATIONS = YES;
				CLANG_WARN_DIRECT_OBJC_ISA_USAGE = YES_ERROR;
				CLANG_WARN_DOCUMENTATION_COMMENTS = YES;
				CLANG_WARN_EMPTY_BODY = YES;
				CLANG_WARN_ENUM_CONVERSION = YES;
				CLANG_WARN_INFINITE_RECURSION = YES;
				CLANG_WARN_INT_CONVERSION = YES;
				CLANG_WARN_NON_LITERAL_NULL_CONVERSION = YES;
				CLANG_WARN_OBJC_LITERAL_CONVERSION = YES;
				CLANG_WARN_OBJC_ROOT_CLASS = YES_ERROR;
				CLANG_WARN_RANGE_LOOP_ANALYSIS = YES;
				CLANG_WARN_SUSPICIOUS_MOVE = YES;
				CODE_SIGN_IDENTITY = "-";
				COPY_PHASE_STRIP = NO;
				DEAD_CODE_STRIPPING = YES;
				DEBUG_INFORMATION_FORMAT = "dwarf-with-dsym";
				ENABLE_NS_ASSERTIONS = NO;
				ENABLE_STRICT_OBJC_MSGSEND = YES;
				ENABLE_USER_SCRIPT_SANDBOXING = NO;
				GCC_C_LANGUAGE_STANDARD = gnu11;
				GCC_NO_COMMON_BLOCKS = YES;
				GCC_WARN_64_TO_32_BIT_CONVERSION = YES;
				GCC_WARN_ABOUT_RETURN_TYPE = YES_ERROR;
				GCC_WARN_UNINITIALIZED_AUTOS = YES_AGGRESSIVE;
				GCC_WARN_UNUSED_FUNCTION = YES;
				GCC_WARN_UNUSED_VARIABLE = YES;
				MACOSX_DEPLOYMENT_TARGET = 10.14;
				MTL_ENABLE_DEBUG_INFO = NO;
				SDKROOT = macosx;
				SWIFT_COMPILATION_MODE = wholemodule;
				SWIFT_OPTIMIZATION_LEVEL = "-O";
			};
			name = Profile;
		};
		338D0CEA231458BD00FA5F75 /* Profile */ = {
			isa = XCBuildConfiguration;
			baseConfigurationReference = 33E5194F232828860026EE4D /* AppInfo.xcconfig */;
			buildSettings = {
				ASSETCATALOG_COMPILER_APPICON_NAME = AppIcon;
				CLANG_ENABLE_MODULES = YES;
				CODE_SIGN_ENTITLEMENTS = Runner/DebugProfile.entitlements;
				CODE_SIGN_STYLE = Automatic;
				COMBINE_HIDPI_IMAGES = YES;
				INFOPLIST_FILE = Runner/Info.plist;
				LD_RUNPATH_SEARCH_PATHS = (
					"$(inherited)",
					"@executable_path/../Frameworks",
				);
				PROVISIONING_PROFILE_SPECIFIER = "";
				SWIFT_VERSION = 5.0;
			};
			name = Profile;
		};
		338D0CEB231458BD00FA5F75 /* Profile */ = {
			isa = XCBuildConfiguration;
			buildSettings = {
				CODE_SIGN_STYLE = Manual;
				PRODUCT_NAME = "$(TARGET_NAME)";
			};
			name = Profile;
		};
		33CC10F92044A3C60003C045 /* Debug */ = {
			isa = XCBuildConfiguration;
			baseConfigurationReference = 9740EEB21CF90195004384FC /* Debug.xcconfig */;
			buildSettings = {
				ALWAYS_SEARCH_USER_PATHS = NO;
				ASSETCATALOG_COMPILER_GENERATE_SWIFT_ASSET_SYMBOL_EXTENSIONS = YES;
				CLANG_ANALYZER_NONNULL = YES;
				CLANG_ANALYZER_NUMBER_OBJECT_CONVERSION = YES_AGGRESSIVE;
				CLANG_CXX_LANGUAGE_STANDARD = "gnu++14";
				CLANG_CXX_LIBRARY = "libc++";
				CLANG_ENABLE_MODULES = YES;
				CLANG_ENABLE_OBJC_ARC = YES;
				CLANG_WARN_BLOCK_CAPTURE_AUTORELEASING = YES;
				CLANG_WARN_BOOL_CONVERSION = YES;
				CLANG_WARN_CONSTANT_CONVERSION = YES;
				CLANG_WARN_DEPRECATED_OBJC_IMPLEMENTATIONS = YES;
				CLANG_WARN_DIRECT_OBJC_ISA_USAGE = YES_ERROR;
				CLANG_WARN_DOCUMENTATION_COMMENTS = YES;
				CLANG_WARN_EMPTY_BODY = YES;
				CLANG_WARN_ENUM_CONVERSION = YES;
				CLANG_WARN_INFINITE_RECURSION = YES;
				CLANG_WARN_INT_CONVERSION = YES;
				CLANG_WARN_NON_LITERAL_NULL_CONVERSION = YES;
				CLANG_WARN_OBJC_LITERAL_CONVERSION = YES;
				CLANG_WARN_OBJC_ROOT_CLASS = YES_ERROR;
				CLANG_WARN_RANGE_LOOP_ANALYSIS = YES;
				CLANG_WARN_SUSPICIOUS_MOVE = YES;
				CODE_SIGN_IDENTITY = "-";
				COPY_PHASE_STRIP = NO;
				DEAD_CODE_STRIPPING = YES;
				DEBUG_INFORMATION_FORMAT = dwarf;
				ENABLE_STRICT_OBJC_MSGSEND = YES;
				ENABLE_TESTABILITY = YES;
				ENABLE_USER_SCRIPT_SANDBOXING = NO;
				GCC_C_LANGUAGE_STANDARD = gnu11;
				GCC_DYNAMIC_NO_PIC = NO;
				GCC_NO_COMMON_BLOCKS = YES;
				GCC_OPTIMIZATION_LEVEL = 0;
				GCC_PREPROCESSOR_DEFINITIONS = (
					"DEBUG=1",
					"$(inherited)",
				);
				GCC_WARN_64_TO_32_BIT_CONVERSION = YES;
				GCC_WARN_ABOUT_RETURN_TYPE = YES_ERROR;
				GCC_WARN_UNINITIALIZED_AUTOS = YES_AGGRESSIVE;
				GCC_WARN_UNUSED_FUNCTION = YES;
				GCC_WARN_UNUSED_VARIABLE = YES;
				MACOSX_DEPLOYMENT_TARGET = 10.14;
				MTL_ENABLE_DEBUG_INFO = YES;
				ONLY_ACTIVE_ARCH = YES;
				SDKROOT = macosx;
				SWIFT_ACTIVE_COMPILATION_CONDITIONS = DEBUG;
				SWIFT_OPTIMIZATION_LEVEL = "-Onone";
			};
			name = Debug;
		};
		33CC10FA2044A3C60003C045 /* Release */ = {
			isa = XCBuildConfiguration;
			baseConfigurationReference = 7AFA3C8E1D35360C0083082E /* Release.xcconfig */;
			buildSettings = {
				ALWAYS_SEARCH_USER_PATHS = NO;
				ASSETCATALOG_COMPILER_GENERATE_SWIFT_ASSET_SYMBOL_EXTENSIONS = YES;
				CLANG_ANALYZER_NONNULL = YES;
				CLANG_ANALYZER_NUMBER_OBJECT_CONVERSION = YES_AGGRESSIVE;
				CLANG_CXX_LANGUAGE_STANDARD = "gnu++14";
				CLANG_CXX_LIBRARY = "libc++";
				CLANG_ENABLE_MODULES = YES;
				CLANG_ENABLE_OBJC_ARC = YES;
				CLANG_WARN_BLOCK_CAPTURE_AUTORELEASING = YES;
				CLANG_WARN_BOOL_CONVERSION = YES;
				CLANG_WARN_CONSTANT_CONVERSION = YES;
				CLANG_WARN_DEPRECATED_OBJC_IMPLEMENTATIONS = YES;
				CLANG_WARN_DIRECT_OBJC_ISA_USAGE = YES_ERROR;
				CLANG_WARN_DOCUMENTATION_COMMENTS = YES;
				CLANG_WARN_EMPTY_BODY = YES;
				CLANG_WARN_ENUM_CONVERSION = YES;
				CLANG_WARN_INFINITE_RECURSION = YES;
				CLANG_WARN_INT_CONVERSION = YES;
				CLANG_WARN_NON_LITERAL_NULL_CONVERSION = YES;
				CLANG_WARN_OBJC_LITERAL_CONVERSION = YES;
				CLANG_WARN_OBJC_ROOT_CLASS = YES_ERROR;
				CLANG_WARN_RANGE_LOOP_ANALYSIS = YES;
				CLANG_WARN_SUSPICIOUS_MOVE = YES;
				CODE_SIGN_IDENTITY = "-";
				COPY_PHASE_STRIP = NO;
				DEAD_CODE_STRIPPING = YES;
				DEBUG_INFORMATION_FORMAT = "dwarf-with-dsym";
				ENABLE_NS_ASSERTIONS = NO;
				ENABLE_STRICT_OBJC_MSGSEND = YES;
				ENABLE_USER_SCRIPT_SANDBOXING = NO;
				GCC_C_LANGUAGE_STANDARD = gnu11;
				GCC_NO_COMMON_BLOCKS = YES;
				GCC_WARN_64_TO_32_BIT_CONVERSION = YES;
				GCC_WARN_ABOUT_RETURN_TYPE = YES_ERROR;
				GCC_WARN_UNINITIALIZED_AUTOS = YES_AGGRESSIVE;
				GCC_WARN_UNUSED_FUNCTION = YES;
				GCC_WARN_UNUSED_VARIABLE = YES;
				MACOSX_DEPLOYMENT_TARGET = 10.14;
				MTL_ENABLE_DEBUG_INFO = NO;
				SDKROOT = macosx;
				SWIFT_COMPILATION_MODE = wholemodule;
				SWIFT_OPTIMIZATION_LEVEL = "-O";
			};
			name = Release;
		};
		33CC10FC2044A3C60003C045 /* Debug */ = {
			isa = XCBuildConfiguration;
			baseConfigurationReference = 33E5194F232828860026EE4D /* AppInfo.xcconfig */;
			buildSettings = {
				ASSETCATALOG_COMPILER_APPICON_NAME = AppIcon;
				CLANG_ENABLE_MODULES = YES;
				CODE_SIGN_ENTITLEMENTS = Runner/DebugProfile.entitlements;
				CODE_SIGN_STYLE = Automatic;
				COMBINE_HIDPI_IMAGES = YES;
				INFOPLIST_FILE = Runner/Info.plist;
				LD_RUNPATH_SEARCH_PATHS = (
					"$(inherited)",
					"@executable_path/../Frameworks",
				);
				PROVISIONING_PROFILE_SPECIFIER = "";
				SWIFT_OPTIMIZATION_LEVEL = "-Onone";
				SWIFT_VERSION = 5.0;
			};
			name = Debug;
		};
		33CC10FD2044A3C60003C045 /* Release */ = {
			isa = XCBuildConfiguration;
			baseConfigurationReference = 33E5194F232828860026EE4D /* AppInfo.xcconfig */;
			buildSettings = {
				ASSETCATALOG_COMPILER_APPICON_NAME = AppIcon;
				CLANG_ENABLE_MODULES = YES;
				CODE_SIGN_ENTITLEMENTS = Runner/Release.entitlements;
				CODE_SIGN_STYLE = Automatic;
				COMBINE_HIDPI_IMAGES = YES;
				INFOPLIST_FILE = Runner/Info.plist;
				LD_RUNPATH_SEARCH_PATHS = (
					"$(inherited)",
					"@executable_path/../Frameworks",
				);
				PROVISIONING_PROFILE_SPECIFIER = "";
				SWIFT_VERSION = 5.0;
			};
			name = Release;
		};
		33CC111C2044C6BA0003C045 /* Debug */ = {
			isa = XCBuildConfiguration;
			buildSettings = {
				CODE_SIGN_STYLE = Manual;
				PRODUCT_NAME = "$(TARGET_NAME)";
			};
			name = Debug;
		};
		33CC111D2044C6BA0003C045 /* Release */ = {
			isa = XCBuildConfiguration;
			buildSettings = {
				CODE_SIGN_STYLE = Automatic;
				PRODUCT_NAME = "$(TARGET_NAME)";
			};
			name = Release;
		};
/* End XCBuildConfiguration section */

/* Begin XCConfigurationList section */
		331C80DE294CF71000263BE5 /* Build configuration list for PBXNativeTarget "RunnerTests" */ = {
			isa = XCConfigurationList;
			buildConfigurations = (
				331C80DB294CF71000263BE5 /* Debug */,
				331C80DC294CF71000263BE5 /* Release */,
				331C80DD294CF71000263BE5 /* Profile */,
			);
			defaultConfigurationIsVisible = 0;
			defaultConfigurationName = Release;
		};
		33CC10E82044A3C60003C045 /* Build configuration list for PBXProject "Runner" */ = {
			isa = XCConfigurationList;
			buildConfigurations = (
				33CC10F92044A3C60003C045 /* Debug */,
				33CC10FA2044A3C60003C045 /* Release */,
				338D0CE9231458BD00FA5F75 /* Profile */,
			);
			defaultConfigurationIsVisible = 0;
			defaultConfigurationName = Release;
		};
		33CC10FB2044A3C60003C045 /* Build configuration list for PBXNativeTarget "Runner" */ = {
			isa = XCConfigurationList;
			buildConfigurations = (
				33CC10FC2044A3C60003C045 /* Debug */,
				33CC10FD2044A3C60003C045 /* Release */,
				338D0CEA231458BD00FA5F75 /* Profile */,
			);
			defaultConfigurationIsVisible = 0;
			defaultConfigurationName = Release;
		};
		33CC111B2044C6BA0003C045 /* Build configuration list for PBXAggregateTarget "Flutter Assemble" */ = {
			isa = XCConfigurationList;
			buildConfigurations = (
				33CC111C2044C6BA0003C045 /* Debug */,
				33CC111D2044C6BA0003C045 /* Release */,
				338D0CEB231458BD00FA5F75 /* Profile */,
			);
			defaultConfigurationIsVisible = 0;
			defaultConfigurationName = Release;
		};
/* End XCConfigurationList section */
	};
	rootObject = 33CC10E52044A3C60003C045 /* Project object */;
}

</file>
<file path="macos/Runner.xcworkspace/xcshareddata/IDEWorkspaceChecks.plist">
<?xml version="1.0" encoding="UTF-8"?>
<!DOCTYPE plist PUBLIC "-//Apple//DTD PLIST 1.0//EN" "http://www.apple.com/DTDs/PropertyList-1.0.dtd">
<plist version="1.0">
<dict>
	<key>IDEDidComputeMac32BitWarning</key>
	<true/>
</dict>
</plist>

</file>
<file path="macos/Runner.xcworkspace/contents.xcworkspacedata">
<?xml version="1.0" encoding="UTF-8"?>
<Workspace
   version = "1.0">
   <FileRef
      location = "group:Runner.xcodeproj">
   </FileRef>
</Workspace>

</file>
<file path="macos/RunnerTests/RunnerTests.swift">
import Cocoa
import FlutterMacOS
import XCTest

class RunnerTests: XCTestCase {

  func testExample() {
    // If you add code to the Runner application, consider adding tests here.
    // See https://developer.apple.com/documentation/xctest for more information about using XCTest.
  }

}

</file>
<file path="macos/.gitignore">
# Flutter-related
**/Flutter/ephemeral/
**/Pods/

# Xcode-related
**/dgph
**/xcuserdata/

</file>
<file path="macos/Podfile">
platform :osx, '10.14'

# CocoaPods analytics sends network stats synchronously affecting flutter build latency.
ENV['COCOAPODS_DISABLE_STATS'] = 'true'

project 'Runner', {
  'Debug' => :debug,
  'Profile' => :release,
  'Release' => :release,
}

def flutter_root
  generated_xcode_build_settings_path = File.expand_path(File.join('..', 'Flutter', 'ephemeral', 'Flutter-Generated.xcconfig'), __FILE__)
  unless File.exist?(generated_xcode_build_settings_path)
    raise "#{generated_xcode_build_settings_path} must exist. If you're running pod install manually, make sure \"flutter pub get\" is executed first"
  end

  File.foreach(generated_xcode_build_settings_path) do |line|
    matches = line.match(/FLUTTER_ROOT\=(.*)/)
    return matches[1].strip if matches
  end
  raise "FLUTTER_ROOT not found in #{generated_xcode_build_settings_path}. Try deleting Flutter-Generated.xcconfig, then run \"flutter pub get\""
end

require File.expand_path(File.join('packages', 'flutter_tools', 'bin', 'podhelper'), flutter_root)

flutter_macos_podfile_setup

target 'Runner' do
  use_frameworks!

  flutter_install_all_macos_pods File.dirname(File.realpath(__FILE__))
  target 'RunnerTests' do
    inherit! :search_paths
  end
end

post_install do |installer|
  installer.pods_project.targets.each do |target|
    flutter_additional_macos_build_settings(target)
  end
end

</file>
<file path="supabase/.branches/_current_branch">
main
</file>
<file path="supabase/functions/get-nutrition/index.ts">
import { serve } from "https://deno.land/std@0.168.0/http/server.ts";
import { mockNutritionData, NutritionData } from "./mock-data.ts";

// Allow random variation in mock data to simulate different recipes
function getVariedMockData(servings: number = 1): NutritionData {
  const variation = 0.8 + Math.random() * 0.4; // Random between 0.8 and 1.2
  const data = JSON.parse(JSON.stringify(mockNutritionData)); // Deep clone

  // Vary the main values and adjust for servings
  const servingMultiplier = variation * servings;
  data.calories = Math.round(data.calories * servingMultiplier);
  data.servings = servings;

  // Update nutrient values
  ["protein", "fat", "carbs", "fiber", "sugar"].forEach((nutrient) => {
    data[nutrient].value = +(data[nutrient].value * servingMultiplier).toFixed(
      1
    );
  });

  // Sodium often varies more
  data.sodium.value = Math.round(
    data.sodium.value * servingMultiplier * (0.9 + Math.random() * 0.2)
  );

  return data;
}

serve(async (req) => {
  // Enable CORS
  const headers = new Headers({
    "Content-Type": "application/json",
    "Access-Control-Allow-Origin": "*",
    "Access-Control-Allow-Headers":
      "authorization, x-client-info, apikey, content-type",
  });

  // Handle preflight requests
  if (req.method === "OPTIONS") {
    return new Response(null, { headers });
  }

  try {
    // Parse request body
    const { ingredients, servings = 1 } = await req.json();

    if (
      !ingredients ||
      !Array.isArray(ingredients) ||
      ingredients.length === 0
    ) {
      return new Response(
        JSON.stringify({ error: "Invalid ingredients array" }),
        { status: 400, headers }
      );
    }

    // Get varied mock data based on servings
    const nutritionData = getVariedMockData(servings);

    // Return the nutrition data
    return new Response(JSON.stringify(nutritionData), { headers });
  } catch (error) {
    console.error("Error processing nutrition request:", error);
    return new Response(
      JSON.stringify({
        error: "Internal server error",
        details: error.message,
      }),
      { status: 500, headers: { "Content-Type": "application/json" } }
    );
  }
});

</file>
<file path="supabase/functions/get-nutrition/mock-data.ts">
// Detailed mock nutrition data with comprehensive nutrient breakdown
export type NutrientValue = {
  value: number;
  unit: string;
  label: string;
  dailyValue?: number; // Percentage of daily recommended value
};

export type FatBreakdown = {
  saturated: NutrientValue;
  polyunsaturated: NutrientValue;
  monounsaturated: NutrientValue;
  trans: NutrientValue;
  omega3: NutrientValue;
  omega6: NutrientValue;
};

export type VitaminData = {
  A: NutrientValue;
  C: NutrientValue;
  D: NutrientValue;
  E: NutrientValue;
  K: NutrientValue;
  B1: NutrientValue;
  B2: NutrientValue;
  B3: NutrientValue;
  B6: NutrientValue;
  B12: NutrientValue;
  folate: NutrientValue;
};

export type MineralData = {
  calcium: NutrientValue;
  iron: NutrientValue;
  magnesium: NutrientValue;
  phosphorus: NutrientValue;
  potassium: NutrientValue;
  zinc: NutrientValue;
  selenium: NutrientValue;
};

export type NutritionData = {
  calories: number;
  protein: NutrientValue;
  fat: NutrientValue & { breakdown: FatBreakdown };
  carbs: NutrientValue;
  fiber: NutrientValue;
  sugar: NutrientValue;
  sodium: NutrientValue;
  cholesterol: NutrientValue;
  vitamins: VitaminData;
  minerals: MineralData;
  dietLabels: string[];
  healthLabels: string[];
  cautions: string[];
  servingWeight: number;
  servings: number;
};

export const mockNutritionData: NutritionData = {
  calories: 245,
  protein: {
    value: 12.5,
    unit: 'g',
    label: 'Protein',
    dailyValue: 25
  },
  fat: {
    value: 8.3,
    unit: 'g',
    label: 'Total Fat',
    dailyValue: 13,
    breakdown: {
      saturated: {
        value: 1.2,
        unit: 'g',
        label: 'Saturated Fat',
        dailyValue: 6
      },
      polyunsaturated: {
        value: 2.1,
        unit: 'g',
        label: 'Polyunsaturated Fat',
        dailyValue: 0
      },
      monounsaturated: {
        value: 4.5,
        unit: 'g',
        label: 'Monounsaturated Fat',
        dailyValue: 0
      },
      trans: {
        value: 0,
        unit: 'g',
        label: 'Trans Fat',
        dailyValue: 0
      },
      omega3: {
        value: 0.8,
        unit: 'g',
        label: 'Omega-3 Fatty Acids',
        dailyValue: 0
      },
      omega6: {
        value: 1.3,
        unit: 'g',
        label: 'Omega-6 Fatty Acids',
        dailyValue: 0
      }
    }
  },
  carbs: {
    value: 30.2,
    unit: 'g',
    label: 'Total Carbs',
    dailyValue: 10
  },
  fiber: {
    value: 4.5,
    unit: 'g',
    label: 'Dietary Fiber',
    dailyValue: 18
  },
  sugar: {
    value: 3.2,
    unit: 'g',
    label: 'Total Sugars',
    dailyValue: 6
  },
  sodium: {
    value: 320,
    unit: 'mg',
    label: 'Sodium',
    dailyValue: 13
  },
  cholesterol: {
    value: 25,
    unit: 'mg',
    label: 'Cholesterol',
    dailyValue: 8
  },
  vitamins: {
    A: { value: 800, unit: 'IU', label: 'Vitamin A', dailyValue: 16 },
    C: { value: 14, unit: 'mg', label: 'Vitamin C', dailyValue: 15 },
    D: { value: 2, unit: 'mcg', label: 'Vitamin D', dailyValue: 10 },
    E: { value: 1.2, unit: 'mg', label: 'Vitamin E', dailyValue: 8 },
    K: { value: 15, unit: 'mcg', label: 'Vitamin K', dailyValue: 12 },
    B1: { value: 0.3, unit: 'mg', label: 'Thiamin (B1)', dailyValue: 25 },
    B2: { value: 0.4, unit: 'mg', label: 'Riboflavin (B2)', dailyValue: 30 },
    B3: { value: 4.2, unit: 'mg', label: 'Niacin (B3)', dailyValue: 26 },
    B6: { value: 0.5, unit: 'mg', label: 'Vitamin B6', dailyValue: 29 },
    B12: { value: 0.8, unit: 'mcg', label: 'Vitamin B12', dailyValue: 33 },
    folate: { value: 120, unit: 'mcg', label: 'Folate', dailyValue: 30 }
  },
  minerals: {
    calcium: { value: 120, unit: 'mg', label: 'Calcium', dailyValue: 12 },
    iron: { value: 2.7, unit: 'mg', label: 'Iron', dailyValue: 15 },
    magnesium: { value: 45, unit: 'mg', label: 'Magnesium', dailyValue: 11 },
    phosphorus: { value: 210, unit: 'mg', label: 'Phosphorus', dailyValue: 17 },
    potassium: { value: 320, unit: 'mg', label: 'Potassium', dailyValue: 8 },
    zinc: { value: 1.8, unit: 'mg', label: 'Zinc', dailyValue: 16 },
    selenium: { value: 15, unit: 'mcg', label: 'Selenium', dailyValue: 27 }
  },
  dietLabels: ['Low Fat', 'High Fiber'],
  healthLabels: ['Good Source of Protein', 'Vegetarian'],
  cautions: [],
  servingWeight: 100,
  servings: 1
};

</file>
<file path="supabase/.gitignore">
# Supabase
.branches
.temp

# dotenvx
.env.keys
.env.local
.env.*.local

</file>
<file path="supabase/config.toml">
# For detailed configuration reference documentation, visit:
# https://supabase.com/docs/guides/local-development/cli/config
# A string used to distinguish different Supabase projects on the same host. Defaults to the
# working directory name when running `supabase init`.
project_id = "foodster"

[api]
enabled = true
# Port to use for the API URL.
port = 54321
# Schemas to expose in your API. Tables, views and stored procedures in this schema will get API
# endpoints. `public` and `graphql_public` schemas are included by default.
schemas = ["public", "graphql_public"]
# Extra schemas to add to the search_path of every request.
extra_search_path = ["public", "extensions"]
# The maximum number of rows returns from a view, table, or stored procedure. Limits payload size
# for accidental or malicious requests.
max_rows = 1000

[api.tls]
# Enable HTTPS endpoints locally using a self-signed certificate.
enabled = false

[db]
# Port to use for the local database URL.
port = 54322
# Port used by db diff command to initialize the shadow database.
shadow_port = 54320
# The database major version to use. This has to be the same as your remote database's. Run `SHOW
# server_version;` on the remote database to check.
major_version = 17

[db.pooler]
enabled = false
# Port to use for the local connection pooler.
port = 54329
# Specifies when a server connection can be reused by other clients.
# Configure one of the supported pooler modes: `transaction`, `session`.
pool_mode = "transaction"
# How many server connections to allow per user/database pair.
default_pool_size = 20
# Maximum number of client connections allowed.
max_client_conn = 100

# [db.vault]
# secret_key = "env(SECRET_VALUE)"

[db.migrations]
# If disabled, migrations will be skipped during a db push or reset.
enabled = true
# Specifies an ordered list of schema files that describe your database.
# Supports glob patterns relative to supabase directory: "./schemas/*.sql"
schema_paths = []

[db.seed]
# If enabled, seeds the database after migrations during a db reset.
enabled = true
# Specifies an ordered list of seed files to load during db reset.
# Supports glob patterns relative to supabase directory: "./seeds/*.sql"
sql_paths = ["./seed.sql"]

[realtime]
enabled = true
# Bind realtime via either IPv4 or IPv6. (default: IPv4)
# ip_version = "IPv6"
# The maximum length in bytes of HTTP request headers. (default: 4096)
# max_header_length = 4096

[studio]
enabled = true
# Port to use for Supabase Studio.
port = 54323
# External URL of the API server that frontend connects to.
api_url = "http://127.0.0.1"
# OpenAI API Key to use for Supabase AI in the Supabase Studio.
openai_api_key = "env(OPENAI_API_KEY)"

# Email testing server. Emails sent with the local dev setup are not actually sent - rather, they
# are monitored, and you can view the emails that would have been sent from the web interface.
[inbucket]
enabled = true
# Port to use for the email testing server web interface.
port = 54324
# Uncomment to expose additional ports for testing user applications that send emails.
# smtp_port = 54325
# pop3_port = 54326
# admin_email = "admin@email.com"
# sender_name = "Admin"

[storage]
enabled = true
# The maximum file size allowed (e.g. "5MB", "500KB").
file_size_limit = "50MiB"

# Image transformation API is available to Supabase Pro plan.
# [storage.image_transformation]
# enabled = true

# Uncomment to configure local storage buckets
# [storage.buckets.images]
# public = false
# file_size_limit = "50MiB"
# allowed_mime_types = ["image/png", "image/jpeg"]
# objects_path = "./images"

[auth]
enabled = true
# The base URL of your website. Used as an allow-list for redirects and for constructing URLs used
# in emails.
site_url = "http://127.0.0.1:3000"
# A list of *exact* URLs that auth providers are permitted to redirect to post authentication.
additional_redirect_urls = ["https://127.0.0.1:3000"]
# How long tokens are valid for, in seconds. Defaults to 3600 (1 hour), maximum 604,800 (1 week).
jwt_expiry = 3600
# If disabled, the refresh token will never expire.
enable_refresh_token_rotation = true
# Allows refresh tokens to be reused after expiry, up to the specified interval in seconds.
# Requires enable_refresh_token_rotation = true.
refresh_token_reuse_interval = 10
# Allow/disallow new user signups to your project.
enable_signup = true
# Allow/disallow anonymous sign-ins to your project.
enable_anonymous_sign_ins = false
# Allow/disallow testing manual linking of accounts
enable_manual_linking = false
# Passwords shorter than this value will be rejected as weak. Minimum 6, recommended 8 or more.
minimum_password_length = 6
# Passwords that do not meet the following requirements will be rejected as weak. Supported values
# are: `letters_digits`, `lower_upper_letters_digits`, `lower_upper_letters_digits_symbols`
password_requirements = ""

[auth.rate_limit]
# Number of emails that can be sent per hour. Requires auth.email.smtp to be enabled.
email_sent = 2
# Number of SMS messages that can be sent per hour. Requires auth.sms to be enabled.
sms_sent = 30
# Number of anonymous sign-ins that can be made per hour per IP address. Requires enable_anonymous_sign_ins = true.
anonymous_users = 30
# Number of sessions that can be refreshed in a 5 minute interval per IP address.
token_refresh = 150
# Number of sign up and sign-in requests that can be made in a 5 minute interval per IP address (excludes anonymous users).
sign_in_sign_ups = 30
# Number of OTP / Magic link verifications that can be made in a 5 minute interval per IP address.
token_verifications = 30
# Number of Web3 logins that can be made in a 5 minute interval per IP address.
web3 = 30

# Configure one of the supported captcha providers: `hcaptcha`, `turnstile`.
# [auth.captcha]
# enabled = true
# provider = "hcaptcha"
# secret = ""

[auth.email]
# Allow/disallow new user signups via email to your project.
enable_signup = true
# If enabled, a user will be required to confirm any email change on both the old, and new email
# addresses. If disabled, only the new email is required to confirm.
double_confirm_changes = true
# If enabled, users need to confirm their email address before signing in.
enable_confirmations = false
# If enabled, users will need to reauthenticate or have logged in recently to change their password.
secure_password_change = false
# Controls the minimum amount of time that must pass before sending another signup confirmation or password reset email.
max_frequency = "1s"
# Number of characters used in the email OTP.
otp_length = 6
# Number of seconds before the email OTP expires (defaults to 1 hour).
otp_expiry = 3600

# Use a production-ready SMTP server
# [auth.email.smtp]
# enabled = true
# host = "smtp.sendgrid.net"
# port = 587
# user = "apikey"
# pass = "env(SENDGRID_API_KEY)"
# admin_email = "admin@email.com"
# sender_name = "Admin"

# Uncomment to customize email template
# [auth.email.template.invite]
# subject = "You have been invited"
# content_path = "./supabase/templates/invite.html"

[auth.sms]
# Allow/disallow new user signups via SMS to your project.
enable_signup = false
# If enabled, users need to confirm their phone number before signing in.
enable_confirmations = false
# Template for sending OTP to users
template = "Your code is {{ .Code }}"
# Controls the minimum amount of time that must pass before sending another sms otp.
max_frequency = "5s"

# Use pre-defined map of phone number to OTP for testing.
# [auth.sms.test_otp]
# 4152127777 = "123456"

# Configure logged in session timeouts.
# [auth.sessions]
# Force log out after the specified duration.
# timebox = "24h"
# Force log out if the user has been inactive longer than the specified duration.
# inactivity_timeout = "8h"

# This hook runs before a token is issued and allows you to add additional claims based on the authentication method used.
# [auth.hook.custom_access_token]
# enabled = true
# uri = "pg-functions://<database>/<schema>/<hook_name>"

# Configure one of the supported SMS providers: `twilio`, `twilio_verify`, `messagebird`, `textlocal`, `vonage`.
[auth.sms.twilio]
enabled = false
account_sid = ""
message_service_sid = ""
# DO NOT commit your Twilio auth token to git. Use environment variable substitution instead:
auth_token = "env(SUPABASE_AUTH_SMS_TWILIO_AUTH_TOKEN)"

# Multi-factor-authentication is available to Supabase Pro plan.
[auth.mfa]
# Control how many MFA factors can be enrolled at once per user.
max_enrolled_factors = 10

# Control MFA via App Authenticator (TOTP)
[auth.mfa.totp]
enroll_enabled = false
verify_enabled = false

# Configure MFA via Phone Messaging
[auth.mfa.phone]
enroll_enabled = false
verify_enabled = false
otp_length = 6
template = "Your code is {{ .Code }}"
max_frequency = "5s"

# Configure MFA via WebAuthn
# [auth.mfa.web_authn]
# enroll_enabled = true
# verify_enabled = true

# Use an external OAuth provider. The full list of providers are: `apple`, `azure`, `bitbucket`,
# `discord`, `facebook`, `github`, `gitlab`, `google`, `keycloak`, `linkedin_oidc`, `notion`, `twitch`,
# `twitter`, `slack`, `spotify`, `workos`, `zoom`.
[auth.external.apple]
enabled = false
client_id = ""
# DO NOT commit your OAuth provider secret to git. Use environment variable substitution instead:
secret = "env(SUPABASE_AUTH_EXTERNAL_APPLE_SECRET)"
# Overrides the default auth redirectUrl.
redirect_uri = ""
# Overrides the default auth provider URL. Used to support self-hosted gitlab, single-tenant Azure,
# or any other third-party OIDC providers.
url = ""
# If enabled, the nonce check will be skipped. Required for local sign in with Google auth.
skip_nonce_check = false

# Allow Solana wallet holders to sign in to your project via the Sign in with Solana (SIWS, EIP-4361) standard.
# You can configure "web3" rate limit in the [auth.rate_limit] section and set up [auth.captcha] if self-hosting.
[auth.web3.solana]
enabled = false

# Use Firebase Auth as a third-party provider alongside Supabase Auth.
[auth.third_party.firebase]
enabled = false
# project_id = "my-firebase-project"

# Use Auth0 as a third-party provider alongside Supabase Auth.
[auth.third_party.auth0]
enabled = false
# tenant = "my-auth0-tenant"
# tenant_region = "us"

# Use AWS Cognito (Amplify) as a third-party provider alongside Supabase Auth.
[auth.third_party.aws_cognito]
enabled = false
# user_pool_id = "my-user-pool-id"
# user_pool_region = "us-east-1"

# Use Clerk as a third-party provider alongside Supabase Auth.
[auth.third_party.clerk]
enabled = false
# Obtain from https://clerk.com/setup/supabase
# domain = "example.clerk.accounts.dev"

[edge_runtime]
enabled = true
# Configure one of the supported request policies: `oneshot`, `per_worker`.
# Use `oneshot` for hot reload, or `per_worker` for load testing.
policy = "oneshot"
# Port to attach the Chrome inspector for debugging edge functions.
inspector_port = 8083
# The Deno major version to use.
deno_version = 1

# [edge_runtime.secrets]
# secret_key = "env(SECRET_VALUE)"

[analytics]
enabled = true
port = 54327
# Configure one of the supported backends: `postgres`, `bigquery`.
backend = "postgres"

# Experimental features may be deprecated any time
[experimental]
# Configures Postgres storage engine to use OrioleDB (S3)
orioledb_version = ""
# Configures S3 bucket URL, eg. <bucket_name>.s3-<region>.amazonaws.com
s3_host = "env(S3_HOST)"
# Configures S3 bucket region, eg. us-east-1
s3_region = "env(S3_REGION)"
# Configures AWS_ACCESS_KEY_ID for S3 bucket
s3_access_key = "env(S3_ACCESS_KEY)"
# Configures AWS_SECRET_ACCESS_KEY for S3 bucket
s3_secret_key = "env(S3_SECRET_KEY)"

</file>
<file path="test/widget_test.dart">
// This is a basic Flutter widget test.
//
// To perform an interaction with a widget in your test, use the WidgetTester
// utility in the flutter_test package. For example, you can send tap and scroll
// gestures. You can also use WidgetTester to find child widgets in the widget
// tree, read text, and verify that the values of widget properties are correct.

import 'package:flutter/material.dart';
import 'package:flutter_test/flutter_test.dart';

import 'package:foodster/main.dart';

void main() {
  testWidgets('Counter increments smoke test', (WidgetTester tester) async {
    // Build our app and trigger a frame.
    await tester.pumpWidget(const MyApp());

    // Verify that our counter starts at 0.
    expect(find.text('0'), findsOneWidget);
    expect(find.text('1'), findsNothing);

    // Tap the '+' icon and trigger a frame.
    await tester.tap(find.byIcon(Icons.add));
    await tester.pump();

    // Verify that our counter has incremented.
    expect(find.text('0'), findsNothing);
    expect(find.text('1'), findsOneWidget);
  });
}

</file>
<file path="web/index.html">
<!DOCTYPE html>
<html>
<head>
  <!--
    If you are serving your web app in a path other than the root, change the
    href value below to reflect the base path you are serving from.

    The path provided below has to start and end with a slash "/" in order for
    it to work correctly.

    For more details:
    * https://developer.mozilla.org/en-US/docs/Web/HTML/Element/base

    This is a placeholder for base href that will be replaced by the value of
    the `--base-href` argument provided to `flutter build`.
  -->
  <base href="$FLUTTER_BASE_HREF">

  <meta charset="UTF-8">
  <meta content="IE=Edge" http-equiv="X-UA-Compatible">
  <meta name="description" content="A new Flutter project.">

  <!-- iOS meta tags & icons -->
  <meta name="mobile-web-app-capable" content="yes">
  <meta name="apple-mobile-web-app-status-bar-style" content="black">
  <meta name="apple-mobile-web-app-title" content="foodster">
  <link rel="apple-touch-icon" href="icons/Icon-192.png">

  <!-- Favicon -->
  <link rel="icon" type="image/png" href="favicon.png"/>

  <title>foodster</title>
  <link rel="manifest" href="manifest.json">
</head>
<body>
  <script src="flutter_bootstrap.js" async></script>
</body>
</html>

</file>
<file path="web/manifest.json">
{
    "name": "foodster",
    "short_name": "foodster",
    "start_url": ".",
    "display": "standalone",
    "background_color": "#0175C2",
    "theme_color": "#0175C2",
    "description": "A new Flutter project.",
    "orientation": "portrait-primary",
    "prefer_related_applications": false,
    "icons": [
        {
            "src": "icons/Icon-192.png",
            "sizes": "192x192",
            "type": "image/png"
        },
        {
            "src": "icons/Icon-512.png",
            "sizes": "512x512",
            "type": "image/png"
        },
        {
            "src": "icons/Icon-maskable-192.png",
            "sizes": "192x192",
            "type": "image/png",
            "purpose": "maskable"
        },
        {
            "src": "icons/Icon-maskable-512.png",
            "sizes": "512x512",
            "type": "image/png",
            "purpose": "maskable"
        }
    ]
}

</file>
<file path="windows/flutter/ephemeral/.plugin_symlinks/app_links">
Error reading file: read /Users/varyable/Workspace/mobileapps/foodster/windows/flutter/ephemeral/.plugin_symlinks/app_links: is a directory
</file>
<file path="windows/flutter/ephemeral/.plugin_symlinks/connectivity_plus">
Error reading file: read /Users/varyable/Workspace/mobileapps/foodster/windows/flutter/ephemeral/.plugin_symlinks/connectivity_plus: is a directory
</file>
<file path="windows/flutter/ephemeral/.plugin_symlinks/flutter_secure_storage_windows">
Error reading file: read /Users/varyable/Workspace/mobileapps/foodster/windows/flutter/ephemeral/.plugin_symlinks/flutter_secure_storage_windows: is a directory
</file>
<file path="windows/flutter/ephemeral/.plugin_symlinks/path_provider_windows">
Error reading file: read /Users/varyable/Workspace/mobileapps/foodster/windows/flutter/ephemeral/.plugin_symlinks/path_provider_windows: is a directory
</file>
<file path="windows/flutter/ephemeral/.plugin_symlinks/shared_preferences_windows">
Error reading file: read /Users/varyable/Workspace/mobileapps/foodster/windows/flutter/ephemeral/.plugin_symlinks/shared_preferences_windows: is a directory
</file>
<file path="windows/flutter/ephemeral/.plugin_symlinks/url_launcher_windows">
Error reading file: read /Users/varyable/Workspace/mobileapps/foodster/windows/flutter/ephemeral/.plugin_symlinks/url_launcher_windows: is a directory
</file>
<file path="windows/flutter/CMakeLists.txt">
# This file controls Flutter-level build steps. It should not be edited.
cmake_minimum_required(VERSION 3.14)

set(EPHEMERAL_DIR "${CMAKE_CURRENT_SOURCE_DIR}/ephemeral")

# Configuration provided via flutter tool.
include(${EPHEMERAL_DIR}/generated_config.cmake)

# TODO: Move the rest of this into files in ephemeral. See
# https://github.com/flutter/flutter/issues/57146.
set(WRAPPER_ROOT "${EPHEMERAL_DIR}/cpp_client_wrapper")

# Set fallback configurations for older versions of the flutter tool.
if (NOT DEFINED FLUTTER_TARGET_PLATFORM)
  set(FLUTTER_TARGET_PLATFORM "windows-x64")
endif()

# === Flutter Library ===
set(FLUTTER_LIBRARY "${EPHEMERAL_DIR}/flutter_windows.dll")

# Published to parent scope for install step.
set(FLUTTER_LIBRARY ${FLUTTER_LIBRARY} PARENT_SCOPE)
set(FLUTTER_ICU_DATA_FILE "${EPHEMERAL_DIR}/icudtl.dat" PARENT_SCOPE)
set(PROJECT_BUILD_DIR "${PROJECT_DIR}/build/" PARENT_SCOPE)
set(AOT_LIBRARY "${PROJECT_DIR}/build/windows/app.so" PARENT_SCOPE)

list(APPEND FLUTTER_LIBRARY_HEADERS
  "flutter_export.h"
  "flutter_windows.h"
  "flutter_messenger.h"
  "flutter_plugin_registrar.h"
  "flutter_texture_registrar.h"
)
list(TRANSFORM FLUTTER_LIBRARY_HEADERS PREPEND "${EPHEMERAL_DIR}/")
add_library(flutter INTERFACE)
target_include_directories(flutter INTERFACE
  "${EPHEMERAL_DIR}"
)
target_link_libraries(flutter INTERFACE "${FLUTTER_LIBRARY}.lib")
add_dependencies(flutter flutter_assemble)

# === Wrapper ===
list(APPEND CPP_WRAPPER_SOURCES_CORE
  "core_implementations.cc"
  "standard_codec.cc"
)
list(TRANSFORM CPP_WRAPPER_SOURCES_CORE PREPEND "${WRAPPER_ROOT}/")
list(APPEND CPP_WRAPPER_SOURCES_PLUGIN
  "plugin_registrar.cc"
)
list(TRANSFORM CPP_WRAPPER_SOURCES_PLUGIN PREPEND "${WRAPPER_ROOT}/")
list(APPEND CPP_WRAPPER_SOURCES_APP
  "flutter_engine.cc"
  "flutter_view_controller.cc"
)
list(TRANSFORM CPP_WRAPPER_SOURCES_APP PREPEND "${WRAPPER_ROOT}/")

# Wrapper sources needed for a plugin.
add_library(flutter_wrapper_plugin STATIC
  ${CPP_WRAPPER_SOURCES_CORE}
  ${CPP_WRAPPER_SOURCES_PLUGIN}
)
apply_standard_settings(flutter_wrapper_plugin)
set_target_properties(flutter_wrapper_plugin PROPERTIES
  POSITION_INDEPENDENT_CODE ON)
set_target_properties(flutter_wrapper_plugin PROPERTIES
  CXX_VISIBILITY_PRESET hidden)
target_link_libraries(flutter_wrapper_plugin PUBLIC flutter)
target_include_directories(flutter_wrapper_plugin PUBLIC
  "${WRAPPER_ROOT}/include"
)
add_dependencies(flutter_wrapper_plugin flutter_assemble)

# Wrapper sources needed for the runner.
add_library(flutter_wrapper_app STATIC
  ${CPP_WRAPPER_SOURCES_CORE}
  ${CPP_WRAPPER_SOURCES_APP}
)
apply_standard_settings(flutter_wrapper_app)
target_link_libraries(flutter_wrapper_app PUBLIC flutter)
target_include_directories(flutter_wrapper_app PUBLIC
  "${WRAPPER_ROOT}/include"
)
add_dependencies(flutter_wrapper_app flutter_assemble)

# === Flutter tool backend ===
# _phony_ is a non-existent file to force this command to run every time,
# since currently there's no way to get a full input/output list from the
# flutter tool.
set(PHONY_OUTPUT "${CMAKE_CURRENT_BINARY_DIR}/_phony_")
set_source_files_properties("${PHONY_OUTPUT}" PROPERTIES SYMBOLIC TRUE)
add_custom_command(
  OUTPUT ${FLUTTER_LIBRARY} ${FLUTTER_LIBRARY_HEADERS}
    ${CPP_WRAPPER_SOURCES_CORE} ${CPP_WRAPPER_SOURCES_PLUGIN}
    ${CPP_WRAPPER_SOURCES_APP}
    ${PHONY_OUTPUT}
  COMMAND ${CMAKE_COMMAND} -E env
    ${FLUTTER_TOOL_ENVIRONMENT}
    "${FLUTTER_ROOT}/packages/flutter_tools/bin/tool_backend.bat"
      ${FLUTTER_TARGET_PLATFORM} $<CONFIG>
  VERBATIM
)
add_custom_target(flutter_assemble DEPENDS
  "${FLUTTER_LIBRARY}"
  ${FLUTTER_LIBRARY_HEADERS}
  ${CPP_WRAPPER_SOURCES_CORE}
  ${CPP_WRAPPER_SOURCES_PLUGIN}
  ${CPP_WRAPPER_SOURCES_APP}
)

</file>
<file path="windows/flutter/generated_plugin_registrant.cc">
//
//  Generated file. Do not edit.
//

// clang-format off

#include "generated_plugin_registrant.h"

#include <app_links/app_links_plugin_c_api.h>
#include <connectivity_plus/connectivity_plus_windows_plugin.h>
#include <flutter_secure_storage_windows/flutter_secure_storage_windows_plugin.h>
#include <url_launcher_windows/url_launcher_windows.h>

void RegisterPlugins(flutter::PluginRegistry* registry) {
  AppLinksPluginCApiRegisterWithRegistrar(
      registry->GetRegistrarForPlugin("AppLinksPluginCApi"));
  ConnectivityPlusWindowsPluginRegisterWithRegistrar(
      registry->GetRegistrarForPlugin("ConnectivityPlusWindowsPlugin"));
  FlutterSecureStorageWindowsPluginRegisterWithRegistrar(
      registry->GetRegistrarForPlugin("FlutterSecureStorageWindowsPlugin"));
  UrlLauncherWindowsRegisterWithRegistrar(
      registry->GetRegistrarForPlugin("UrlLauncherWindows"));
}

</file>
<file path="windows/flutter/generated_plugin_registrant.h">
//
//  Generated file. Do not edit.
//

// clang-format off

#ifndef GENERATED_PLUGIN_REGISTRANT_
#define GENERATED_PLUGIN_REGISTRANT_

#include <flutter/plugin_registry.h>

// Registers Flutter plugins.
void RegisterPlugins(flutter::PluginRegistry* registry);

#endif  // GENERATED_PLUGIN_REGISTRANT_

</file>
<file path="windows/flutter/generated_plugins.cmake">
#
# Generated file, do not edit.
#

list(APPEND FLUTTER_PLUGIN_LIST
  app_links
  connectivity_plus
  flutter_secure_storage_windows
  url_launcher_windows
)

list(APPEND FLUTTER_FFI_PLUGIN_LIST
)

set(PLUGIN_BUNDLED_LIBRARIES)

foreach(plugin ${FLUTTER_PLUGIN_LIST})
  add_subdirectory(flutter/ephemeral/.plugin_symlinks/${plugin}/windows plugins/${plugin})
  target_link_libraries(${BINARY_NAME} PRIVATE ${plugin}_plugin)
  list(APPEND PLUGIN_BUNDLED_LIBRARIES $<TARGET_FILE:${plugin}_plugin>)
  list(APPEND PLUGIN_BUNDLED_LIBRARIES ${${plugin}_bundled_libraries})
endforeach(plugin)

foreach(ffi_plugin ${FLUTTER_FFI_PLUGIN_LIST})
  add_subdirectory(flutter/ephemeral/.plugin_symlinks/${ffi_plugin}/windows plugins/${ffi_plugin})
  list(APPEND PLUGIN_BUNDLED_LIBRARIES ${${ffi_plugin}_bundled_libraries})
endforeach(ffi_plugin)

</file>
<file path="windows/runner/CMakeLists.txt">
cmake_minimum_required(VERSION 3.14)
project(runner LANGUAGES CXX)

# Define the application target. To change its name, change BINARY_NAME in the
# top-level CMakeLists.txt, not the value here, or `flutter run` will no longer
# work.
#
# Any new source files that you add to the application should be added here.
add_executable(${BINARY_NAME} WIN32
  "flutter_window.cpp"
  "main.cpp"
  "utils.cpp"
  "win32_window.cpp"
  "${FLUTTER_MANAGED_DIR}/generated_plugin_registrant.cc"
  "Runner.rc"
  "runner.exe.manifest"
)

# Apply the standard set of build settings. This can be removed for applications
# that need different build settings.
apply_standard_settings(${BINARY_NAME})

# Add preprocessor definitions for the build version.
target_compile_definitions(${BINARY_NAME} PRIVATE "FLUTTER_VERSION=\"${FLUTTER_VERSION}\"")
target_compile_definitions(${BINARY_NAME} PRIVATE "FLUTTER_VERSION_MAJOR=${FLUTTER_VERSION_MAJOR}")
target_compile_definitions(${BINARY_NAME} PRIVATE "FLUTTER_VERSION_MINOR=${FLUTTER_VERSION_MINOR}")
target_compile_definitions(${BINARY_NAME} PRIVATE "FLUTTER_VERSION_PATCH=${FLUTTER_VERSION_PATCH}")
target_compile_definitions(${BINARY_NAME} PRIVATE "FLUTTER_VERSION_BUILD=${FLUTTER_VERSION_BUILD}")

# Disable Windows macros that collide with C++ standard library functions.
target_compile_definitions(${BINARY_NAME} PRIVATE "NOMINMAX")

# Add dependency libraries and include directories. Add any application-specific
# dependencies here.
target_link_libraries(${BINARY_NAME} PRIVATE flutter flutter_wrapper_app)
target_link_libraries(${BINARY_NAME} PRIVATE "dwmapi.lib")
target_include_directories(${BINARY_NAME} PRIVATE "${CMAKE_SOURCE_DIR}")

# Run the Flutter tool portions of the build. This must not be removed.
add_dependencies(${BINARY_NAME} flutter_assemble)

</file>
<file path="windows/runner/flutter_window.cpp">
#include "flutter_window.h"

#include <optional>

#include "flutter/generated_plugin_registrant.h"

FlutterWindow::FlutterWindow(const flutter::DartProject& project)
    : project_(project) {}

FlutterWindow::~FlutterWindow() {}

bool FlutterWindow::OnCreate() {
  if (!Win32Window::OnCreate()) {
    return false;
  }

  RECT frame = GetClientArea();

  // The size here must match the window dimensions to avoid unnecessary surface
  // creation / destruction in the startup path.
  flutter_controller_ = std::make_unique<flutter::FlutterViewController>(
      frame.right - frame.left, frame.bottom - frame.top, project_);
  // Ensure that basic setup of the controller was successful.
  if (!flutter_controller_->engine() || !flutter_controller_->view()) {
    return false;
  }
  RegisterPlugins(flutter_controller_->engine());
  SetChildContent(flutter_controller_->view()->GetNativeWindow());

  flutter_controller_->engine()->SetNextFrameCallback([&]() {
    this->Show();
  });

  // Flutter can complete the first frame before the "show window" callback is
  // registered. The following call ensures a frame is pending to ensure the
  // window is shown. It is a no-op if the first frame hasn't completed yet.
  flutter_controller_->ForceRedraw();

  return true;
}

void FlutterWindow::OnDestroy() {
  if (flutter_controller_) {
    flutter_controller_ = nullptr;
  }

  Win32Window::OnDestroy();
}

LRESULT
FlutterWindow::MessageHandler(HWND hwnd, UINT const message,
                              WPARAM const wparam,
                              LPARAM const lparam) noexcept {
  // Give Flutter, including plugins, an opportunity to handle window messages.
  if (flutter_controller_) {
    std::optional<LRESULT> result =
        flutter_controller_->HandleTopLevelWindowProc(hwnd, message, wparam,
                                                      lparam);
    if (result) {
      return *result;
    }
  }

  switch (message) {
    case WM_FONTCHANGE:
      flutter_controller_->engine()->ReloadSystemFonts();
      break;
  }

  return Win32Window::MessageHandler(hwnd, message, wparam, lparam);
}

</file>
<file path="windows/runner/flutter_window.h">
#ifndef RUNNER_FLUTTER_WINDOW_H_
#define RUNNER_FLUTTER_WINDOW_H_

#include <flutter/dart_project.h>
#include <flutter/flutter_view_controller.h>

#include <memory>

#include "win32_window.h"

// A window that does nothing but host a Flutter view.
class FlutterWindow : public Win32Window {
 public:
  // Creates a new FlutterWindow hosting a Flutter view running |project|.
  explicit FlutterWindow(const flutter::DartProject& project);
  virtual ~FlutterWindow();

 protected:
  // Win32Window:
  bool OnCreate() override;
  void OnDestroy() override;
  LRESULT MessageHandler(HWND window, UINT const message, WPARAM const wparam,
                         LPARAM const lparam) noexcept override;

 private:
  // The project to run.
  flutter::DartProject project_;

  // The Flutter instance hosted by this window.
  std::unique_ptr<flutter::FlutterViewController> flutter_controller_;
};

#endif  // RUNNER_FLUTTER_WINDOW_H_

</file>
<file path="windows/runner/main.cpp">
#include <flutter/dart_project.h>
#include <flutter/flutter_view_controller.h>
#include <windows.h>

#include "flutter_window.h"
#include "utils.h"

int APIENTRY wWinMain(_In_ HINSTANCE instance, _In_opt_ HINSTANCE prev,
                      _In_ wchar_t *command_line, _In_ int show_command) {
  // Attach to console when present (e.g., 'flutter run') or create a
  // new console when running with a debugger.
  if (!::AttachConsole(ATTACH_PARENT_PROCESS) && ::IsDebuggerPresent()) {
    CreateAndAttachConsole();
  }

  // Initialize COM, so that it is available for use in the library and/or
  // plugins.
  ::CoInitializeEx(nullptr, COINIT_APARTMENTTHREADED);

  flutter::DartProject project(L"data");

  std::vector<std::string> command_line_arguments =
      GetCommandLineArguments();

  project.set_dart_entrypoint_arguments(std::move(command_line_arguments));

  FlutterWindow window(project);
  Win32Window::Point origin(10, 10);
  Win32Window::Size size(1280, 720);
  if (!window.Create(L"foodster", origin, size)) {
    return EXIT_FAILURE;
  }
  window.SetQuitOnClose(true);

  ::MSG msg;
  while (::GetMessage(&msg, nullptr, 0, 0)) {
    ::TranslateMessage(&msg);
    ::DispatchMessage(&msg);
  }

  ::CoUninitialize();
  return EXIT_SUCCESS;
}

</file>
<file path="windows/runner/resource.h">
//{{NO_DEPENDENCIES}}
// Microsoft Visual C++ generated include file.
// Used by Runner.rc
//
#define IDI_APP_ICON                    101

// Next default values for new objects
//
#ifdef APSTUDIO_INVOKED
#ifndef APSTUDIO_READONLY_SYMBOLS
#define _APS_NEXT_RESOURCE_VALUE        102
#define _APS_NEXT_COMMAND_VALUE         40001
#define _APS_NEXT_CONTROL_VALUE         1001
#define _APS_NEXT_SYMED_VALUE           101
#endif
#endif

</file>
<file path="windows/runner/runner.exe.manifest">
<?xml version="1.0" encoding="UTF-8" standalone="yes"?>
<assembly xmlns="urn:schemas-microsoft-com:asm.v1" manifestVersion="1.0">
  <application xmlns="urn:schemas-microsoft-com:asm.v3">
    <windowsSettings>
      <dpiAwareness xmlns="http://schemas.microsoft.com/SMI/2016/WindowsSettings">PerMonitorV2</dpiAwareness>
    </windowsSettings>
  </application>
  <compatibility xmlns="urn:schemas-microsoft-com:compatibility.v1">
    <application>
      <!-- Windows 10 and Windows 11 -->
      <supportedOS Id="{8e0f7a12-bfb3-4fe8-b9a5-48fd50a15a9a}"/>
    </application>
  </compatibility>
</assembly>

</file>
<file path="windows/runner/Runner.rc">
// Microsoft Visual C++ generated resource script.
//
#pragma code_page(65001)
#include "resource.h"

#define APSTUDIO_READONLY_SYMBOLS
/////////////////////////////////////////////////////////////////////////////
//
// Generated from the TEXTINCLUDE 2 resource.
//
#include "winres.h"

/////////////////////////////////////////////////////////////////////////////
#undef APSTUDIO_READONLY_SYMBOLS

/////////////////////////////////////////////////////////////////////////////
// English (United States) resources

#if !defined(AFX_RESOURCE_DLL) || defined(AFX_TARG_ENU)
LANGUAGE LANG_ENGLISH, SUBLANG_ENGLISH_US

#ifdef APSTUDIO_INVOKED
/////////////////////////////////////////////////////////////////////////////
//
// TEXTINCLUDE
//

1 TEXTINCLUDE
BEGIN
    "resource.h\0"
END

2 TEXTINCLUDE
BEGIN
    "#include ""winres.h""\r\n"
    "\0"
END

3 TEXTINCLUDE
BEGIN
    "\r\n"
    "\0"
END

#endif    // APSTUDIO_INVOKED


/////////////////////////////////////////////////////////////////////////////
//
// Icon
//

// Icon with lowest ID value placed first to ensure application icon
// remains consistent on all systems.
IDI_APP_ICON            ICON                    "resources\\app_icon.ico"


/////////////////////////////////////////////////////////////////////////////
//
// Version
//

#if defined(FLUTTER_VERSION_MAJOR) && defined(FLUTTER_VERSION_MINOR) && defined(FLUTTER_VERSION_PATCH) && defined(FLUTTER_VERSION_BUILD)
#define VERSION_AS_NUMBER FLUTTER_VERSION_MAJOR,FLUTTER_VERSION_MINOR,FLUTTER_VERSION_PATCH,FLUTTER_VERSION_BUILD
#else
#define VERSION_AS_NUMBER 1,0,0,0
#endif

#if defined(FLUTTER_VERSION)
#define VERSION_AS_STRING FLUTTER_VERSION
#else
#define VERSION_AS_STRING "1.0.0"
#endif

VS_VERSION_INFO VERSIONINFO
 FILEVERSION VERSION_AS_NUMBER
 PRODUCTVERSION VERSION_AS_NUMBER
 FILEFLAGSMASK VS_FFI_FILEFLAGSMASK
#ifdef _DEBUG
 FILEFLAGS VS_FF_DEBUG
#else
 FILEFLAGS 0x0L
#endif
 FILEOS VOS__WINDOWS32
 FILETYPE VFT_APP
 FILESUBTYPE 0x0L
BEGIN
    BLOCK "StringFileInfo"
    BEGIN
        BLOCK "040904e4"
        BEGIN
            VALUE "CompanyName", "com.example" "\0"
            VALUE "FileDescription", "foodster" "\0"
            VALUE "FileVersion", VERSION_AS_STRING "\0"
            VALUE "InternalName", "foodster" "\0"
            VALUE "LegalCopyright", "Copyright (C) 2025 com.example. All rights reserved." "\0"
            VALUE "OriginalFilename", "foodster.exe" "\0"
            VALUE "ProductName", "foodster" "\0"
            VALUE "ProductVersion", VERSION_AS_STRING "\0"
        END
    END
    BLOCK "VarFileInfo"
    BEGIN
        VALUE "Translation", 0x409, 1252
    END
END

#endif    // English (United States) resources
/////////////////////////////////////////////////////////////////////////////



#ifndef APSTUDIO_INVOKED
/////////////////////////////////////////////////////////////////////////////
//
// Generated from the TEXTINCLUDE 3 resource.
//


/////////////////////////////////////////////////////////////////////////////
#endif    // not APSTUDIO_INVOKED

</file>
<file path="windows/runner/utils.cpp">
#include "utils.h"

#include <flutter_windows.h>
#include <io.h>
#include <stdio.h>
#include <windows.h>

#include <iostream>

void CreateAndAttachConsole() {
  if (::AllocConsole()) {
    FILE *unused;
    if (freopen_s(&unused, "CONOUT$", "w", stdout)) {
      _dup2(_fileno(stdout), 1);
    }
    if (freopen_s(&unused, "CONOUT$", "w", stderr)) {
      _dup2(_fileno(stdout), 2);
    }
    std::ios::sync_with_stdio();
    FlutterDesktopResyncOutputStreams();
  }
}

std::vector<std::string> GetCommandLineArguments() {
  // Convert the UTF-16 command line arguments to UTF-8 for the Engine to use.
  int argc;
  wchar_t** argv = ::CommandLineToArgvW(::GetCommandLineW(), &argc);
  if (argv == nullptr) {
    return std::vector<std::string>();
  }

  std::vector<std::string> command_line_arguments;

  // Skip the first argument as it's the binary name.
  for (int i = 1; i < argc; i++) {
    command_line_arguments.push_back(Utf8FromUtf16(argv[i]));
  }

  ::LocalFree(argv);

  return command_line_arguments;
}

std::string Utf8FromUtf16(const wchar_t* utf16_string) {
  if (utf16_string == nullptr) {
    return std::string();
  }
  unsigned int target_length = ::WideCharToMultiByte(
      CP_UTF8, WC_ERR_INVALID_CHARS, utf16_string,
      -1, nullptr, 0, nullptr, nullptr)
    -1; // remove the trailing null character
  int input_length = (int)wcslen(utf16_string);
  std::string utf8_string;
  if (target_length == 0 || target_length > utf8_string.max_size()) {
    return utf8_string;
  }
  utf8_string.resize(target_length);
  int converted_length = ::WideCharToMultiByte(
      CP_UTF8, WC_ERR_INVALID_CHARS, utf16_string,
      input_length, utf8_string.data(), target_length, nullptr, nullptr);
  if (converted_length == 0) {
    return std::string();
  }
  return utf8_string;
}

</file>
<file path="windows/runner/utils.h">
#ifndef RUNNER_UTILS_H_
#define RUNNER_UTILS_H_

#include <string>
#include <vector>

// Creates a console for the process, and redirects stdout and stderr to
// it for both the runner and the Flutter library.
void CreateAndAttachConsole();

// Takes a null-terminated wchar_t* encoded in UTF-16 and returns a std::string
// encoded in UTF-8. Returns an empty std::string on failure.
std::string Utf8FromUtf16(const wchar_t* utf16_string);

// Gets the command line arguments passed in as a std::vector<std::string>,
// encoded in UTF-8. Returns an empty std::vector<std::string> on failure.
std::vector<std::string> GetCommandLineArguments();

#endif  // RUNNER_UTILS_H_

</file>
<file path="windows/runner/win32_window.cpp">
#include "win32_window.h"

#include <dwmapi.h>
#include <flutter_windows.h>

#include "resource.h"

namespace {

/// Window attribute that enables dark mode window decorations.
///
/// Redefined in case the developer's machine has a Windows SDK older than
/// version 10.0.22000.0.
/// See: https://docs.microsoft.com/windows/win32/api/dwmapi/ne-dwmapi-dwmwindowattribute
#ifndef DWMWA_USE_IMMERSIVE_DARK_MODE
#define DWMWA_USE_IMMERSIVE_DARK_MODE 20
#endif

constexpr const wchar_t kWindowClassName[] = L"FLUTTER_RUNNER_WIN32_WINDOW";

/// Registry key for app theme preference.
///
/// A value of 0 indicates apps should use dark mode. A non-zero or missing
/// value indicates apps should use light mode.
constexpr const wchar_t kGetPreferredBrightnessRegKey[] =
  L"Software\\Microsoft\\Windows\\CurrentVersion\\Themes\\Personalize";
constexpr const wchar_t kGetPreferredBrightnessRegValue[] = L"AppsUseLightTheme";

// The number of Win32Window objects that currently exist.
static int g_active_window_count = 0;

using EnableNonClientDpiScaling = BOOL __stdcall(HWND hwnd);

// Scale helper to convert logical scaler values to physical using passed in
// scale factor
int Scale(int source, double scale_factor) {
  return static_cast<int>(source * scale_factor);
}

// Dynamically loads the |EnableNonClientDpiScaling| from the User32 module.
// This API is only needed for PerMonitor V1 awareness mode.
void EnableFullDpiSupportIfAvailable(HWND hwnd) {
  HMODULE user32_module = LoadLibraryA("User32.dll");
  if (!user32_module) {
    return;
  }
  auto enable_non_client_dpi_scaling =
      reinterpret_cast<EnableNonClientDpiScaling*>(
          GetProcAddress(user32_module, "EnableNonClientDpiScaling"));
  if (enable_non_client_dpi_scaling != nullptr) {
    enable_non_client_dpi_scaling(hwnd);
  }
  FreeLibrary(user32_module);
}

}  // namespace

// Manages the Win32Window's window class registration.
class WindowClassRegistrar {
 public:
  ~WindowClassRegistrar() = default;

  // Returns the singleton registrar instance.
  static WindowClassRegistrar* GetInstance() {
    if (!instance_) {
      instance_ = new WindowClassRegistrar();
    }
    return instance_;
  }

  // Returns the name of the window class, registering the class if it hasn't
  // previously been registered.
  const wchar_t* GetWindowClass();

  // Unregisters the window class. Should only be called if there are no
  // instances of the window.
  void UnregisterWindowClass();

 private:
  WindowClassRegistrar() = default;

  static WindowClassRegistrar* instance_;

  bool class_registered_ = false;
};

WindowClassRegistrar* WindowClassRegistrar::instance_ = nullptr;

const wchar_t* WindowClassRegistrar::GetWindowClass() {
  if (!class_registered_) {
    WNDCLASS window_class{};
    window_class.hCursor = LoadCursor(nullptr, IDC_ARROW);
    window_class.lpszClassName = kWindowClassName;
    window_class.style = CS_HREDRAW | CS_VREDRAW;
    window_class.cbClsExtra = 0;
    window_class.cbWndExtra = 0;
    window_class.hInstance = GetModuleHandle(nullptr);
    window_class.hIcon =
        LoadIcon(window_class.hInstance, MAKEINTRESOURCE(IDI_APP_ICON));
    window_class.hbrBackground = 0;
    window_class.lpszMenuName = nullptr;
    window_class.lpfnWndProc = Win32Window::WndProc;
    RegisterClass(&window_class);
    class_registered_ = true;
  }
  return kWindowClassName;
}

void WindowClassRegistrar::UnregisterWindowClass() {
  UnregisterClass(kWindowClassName, nullptr);
  class_registered_ = false;
}

Win32Window::Win32Window() {
  ++g_active_window_count;
}

Win32Window::~Win32Window() {
  --g_active_window_count;
  Destroy();
}

bool Win32Window::Create(const std::wstring& title,
                         const Point& origin,
                         const Size& size) {
  Destroy();

  const wchar_t* window_class =
      WindowClassRegistrar::GetInstance()->GetWindowClass();

  const POINT target_point = {static_cast<LONG>(origin.x),
                              static_cast<LONG>(origin.y)};
  HMONITOR monitor = MonitorFromPoint(target_point, MONITOR_DEFAULTTONEAREST);
  UINT dpi = FlutterDesktopGetDpiForMonitor(monitor);
  double scale_factor = dpi / 96.0;

  HWND window = CreateWindow(
      window_class, title.c_str(), WS_OVERLAPPEDWINDOW,
      Scale(origin.x, scale_factor), Scale(origin.y, scale_factor),
      Scale(size.width, scale_factor), Scale(size.height, scale_factor),
      nullptr, nullptr, GetModuleHandle(nullptr), this);

  if (!window) {
    return false;
  }

  UpdateTheme(window);

  return OnCreate();
}

bool Win32Window::Show() {
  return ShowWindow(window_handle_, SW_SHOWNORMAL);
}

// static
LRESULT CALLBACK Win32Window::WndProc(HWND const window,
                                      UINT const message,
                                      WPARAM const wparam,
                                      LPARAM const lparam) noexcept {
  if (message == WM_NCCREATE) {
    auto window_struct = reinterpret_cast<CREATESTRUCT*>(lparam);
    SetWindowLongPtr(window, GWLP_USERDATA,
                     reinterpret_cast<LONG_PTR>(window_struct->lpCreateParams));

    auto that = static_cast<Win32Window*>(window_struct->lpCreateParams);
    EnableFullDpiSupportIfAvailable(window);
    that->window_handle_ = window;
  } else if (Win32Window* that = GetThisFromHandle(window)) {
    return that->MessageHandler(window, message, wparam, lparam);
  }

  return DefWindowProc(window, message, wparam, lparam);
}

LRESULT
Win32Window::MessageHandler(HWND hwnd,
                            UINT const message,
                            WPARAM const wparam,
                            LPARAM const lparam) noexcept {
  switch (message) {
    case WM_DESTROY:
      window_handle_ = nullptr;
      Destroy();
      if (quit_on_close_) {
        PostQuitMessage(0);
      }
      return 0;

    case WM_DPICHANGED: {
      auto newRectSize = reinterpret_cast<RECT*>(lparam);
      LONG newWidth = newRectSize->right - newRectSize->left;
      LONG newHeight = newRectSize->bottom - newRectSize->top;

      SetWindowPos(hwnd, nullptr, newRectSize->left, newRectSize->top, newWidth,
                   newHeight, SWP_NOZORDER | SWP_NOACTIVATE);

      return 0;
    }
    case WM_SIZE: {
      RECT rect = GetClientArea();
      if (child_content_ != nullptr) {
        // Size and position the child window.
        MoveWindow(child_content_, rect.left, rect.top, rect.right - rect.left,
                   rect.bottom - rect.top, TRUE);
      }
      return 0;
    }

    case WM_ACTIVATE:
      if (child_content_ != nullptr) {
        SetFocus(child_content_);
      }
      return 0;

    case WM_DWMCOLORIZATIONCOLORCHANGED:
      UpdateTheme(hwnd);
      return 0;
  }

  return DefWindowProc(window_handle_, message, wparam, lparam);
}

void Win32Window::Destroy() {
  OnDestroy();

  if (window_handle_) {
    DestroyWindow(window_handle_);
    window_handle_ = nullptr;
  }
  if (g_active_window_count == 0) {
    WindowClassRegistrar::GetInstance()->UnregisterWindowClass();
  }
}

Win32Window* Win32Window::GetThisFromHandle(HWND const window) noexcept {
  return reinterpret_cast<Win32Window*>(
      GetWindowLongPtr(window, GWLP_USERDATA));
}

void Win32Window::SetChildContent(HWND content) {
  child_content_ = content;
  SetParent(content, window_handle_);
  RECT frame = GetClientArea();

  MoveWindow(content, frame.left, frame.top, frame.right - frame.left,
             frame.bottom - frame.top, true);

  SetFocus(child_content_);
}

RECT Win32Window::GetClientArea() {
  RECT frame;
  GetClientRect(window_handle_, &frame);
  return frame;
}

HWND Win32Window::GetHandle() {
  return window_handle_;
}

void Win32Window::SetQuitOnClose(bool quit_on_close) {
  quit_on_close_ = quit_on_close;
}

bool Win32Window::OnCreate() {
  // No-op; provided for subclasses.
  return true;
}

void Win32Window::OnDestroy() {
  // No-op; provided for subclasses.
}

void Win32Window::UpdateTheme(HWND const window) {
  DWORD light_mode;
  DWORD light_mode_size = sizeof(light_mode);
  LSTATUS result = RegGetValue(HKEY_CURRENT_USER, kGetPreferredBrightnessRegKey,
                               kGetPreferredBrightnessRegValue,
                               RRF_RT_REG_DWORD, nullptr, &light_mode,
                               &light_mode_size);

  if (result == ERROR_SUCCESS) {
    BOOL enable_dark_mode = light_mode == 0;
    DwmSetWindowAttribute(window, DWMWA_USE_IMMERSIVE_DARK_MODE,
                          &enable_dark_mode, sizeof(enable_dark_mode));
  }
}

</file>
<file path="windows/runner/win32_window.h">
#ifndef RUNNER_WIN32_WINDOW_H_
#define RUNNER_WIN32_WINDOW_H_

#include <windows.h>

#include <functional>
#include <memory>
#include <string>

// A class abstraction for a high DPI-aware Win32 Window. Intended to be
// inherited from by classes that wish to specialize with custom
// rendering and input handling
class Win32Window {
 public:
  struct Point {
    unsigned int x;
    unsigned int y;
    Point(unsigned int x, unsigned int y) : x(x), y(y) {}
  };

  struct Size {
    unsigned int width;
    unsigned int height;
    Size(unsigned int width, unsigned int height)
        : width(width), height(height) {}
  };

  Win32Window();
  virtual ~Win32Window();

  // Creates a win32 window with |title| that is positioned and sized using
  // |origin| and |size|. New windows are created on the default monitor. Window
  // sizes are specified to the OS in physical pixels, hence to ensure a
  // consistent size this function will scale the inputted width and height as
  // as appropriate for the default monitor. The window is invisible until
  // |Show| is called. Returns true if the window was created successfully.
  bool Create(const std::wstring& title, const Point& origin, const Size& size);

  // Show the current window. Returns true if the window was successfully shown.
  bool Show();

  // Release OS resources associated with window.
  void Destroy();

  // Inserts |content| into the window tree.
  void SetChildContent(HWND content);

  // Returns the backing Window handle to enable clients to set icon and other
  // window properties. Returns nullptr if the window has been destroyed.
  HWND GetHandle();

  // If true, closing this window will quit the application.
  void SetQuitOnClose(bool quit_on_close);

  // Return a RECT representing the bounds of the current client area.
  RECT GetClientArea();

 protected:
  // Processes and route salient window messages for mouse handling,
  // size change and DPI. Delegates handling of these to member overloads that
  // inheriting classes can handle.
  virtual LRESULT MessageHandler(HWND window,
                                 UINT const message,
                                 WPARAM const wparam,
                                 LPARAM const lparam) noexcept;

  // Called when CreateAndShow is called, allowing subclass window-related
  // setup. Subclasses should return false if setup fails.
  virtual bool OnCreate();

  // Called when Destroy is called.
  virtual void OnDestroy();

 private:
  friend class WindowClassRegistrar;

  // OS callback called by message pump. Handles the WM_NCCREATE message which
  // is passed when the non-client area is being created and enables automatic
  // non-client DPI scaling so that the non-client area automatically
  // responds to changes in DPI. All other messages are handled by
  // MessageHandler.
  static LRESULT CALLBACK WndProc(HWND const window,
                                  UINT const message,
                                  WPARAM const wparam,
                                  LPARAM const lparam) noexcept;

  // Retrieves a class instance pointer for |window|
  static Win32Window* GetThisFromHandle(HWND const window) noexcept;

  // Update the window frame's theme to match the system theme.
  static void UpdateTheme(HWND const window);

  bool quit_on_close_ = false;

  // window handle for top level window.
  HWND window_handle_ = nullptr;

  // window handle for hosted content.
  HWND child_content_ = nullptr;
};

#endif  // RUNNER_WIN32_WINDOW_H_

</file>
<file path="windows/.gitignore">
flutter/ephemeral/

# Visual Studio user-specific files.
*.suo
*.user
*.userosscache
*.sln.docstates

# Visual Studio build-related files.
x64/
x86/

# Visual Studio cache files
# files ending in .cache can be ignored
*.[Cc]ache
# but keep track of directories ending in .cache
!*.[Cc]ache/

</file>
<file path="windows/CMakeLists.txt">
# Project-level configuration.
cmake_minimum_required(VERSION 3.14)
project(foodster LANGUAGES CXX)

# The name of the executable created for the application. Change this to change
# the on-disk name of your application.
set(BINARY_NAME "foodster")

# Explicitly opt in to modern CMake behaviors to avoid warnings with recent
# versions of CMake.
cmake_policy(VERSION 3.14...3.25)

# Define build configuration option.
get_property(IS_MULTICONFIG GLOBAL PROPERTY GENERATOR_IS_MULTI_CONFIG)
if(IS_MULTICONFIG)
  set(CMAKE_CONFIGURATION_TYPES "Debug;Profile;Release"
    CACHE STRING "" FORCE)
else()
  if(NOT CMAKE_BUILD_TYPE AND NOT CMAKE_CONFIGURATION_TYPES)
    set(CMAKE_BUILD_TYPE "Debug" CACHE
      STRING "Flutter build mode" FORCE)
    set_property(CACHE CMAKE_BUILD_TYPE PROPERTY STRINGS
      "Debug" "Profile" "Release")
  endif()
endif()
# Define settings for the Profile build mode.
set(CMAKE_EXE_LINKER_FLAGS_PROFILE "${CMAKE_EXE_LINKER_FLAGS_RELEASE}")
set(CMAKE_SHARED_LINKER_FLAGS_PROFILE "${CMAKE_SHARED_LINKER_FLAGS_RELEASE}")
set(CMAKE_C_FLAGS_PROFILE "${CMAKE_C_FLAGS_RELEASE}")
set(CMAKE_CXX_FLAGS_PROFILE "${CMAKE_CXX_FLAGS_RELEASE}")

# Use Unicode for all projects.
add_definitions(-DUNICODE -D_UNICODE)

# Compilation settings that should be applied to most targets.
#
# Be cautious about adding new options here, as plugins use this function by
# default. In most cases, you should add new options to specific targets instead
# of modifying this function.
function(APPLY_STANDARD_SETTINGS TARGET)
  target_compile_features(${TARGET} PUBLIC cxx_std_17)
  target_compile_options(${TARGET} PRIVATE /W4 /WX /wd"4100")
  target_compile_options(${TARGET} PRIVATE /EHsc)
  target_compile_definitions(${TARGET} PRIVATE "_HAS_EXCEPTIONS=0")
  target_compile_definitions(${TARGET} PRIVATE "$<$<CONFIG:Debug>:_DEBUG>")
endfunction()

# Flutter library and tool build rules.
set(FLUTTER_MANAGED_DIR "${CMAKE_CURRENT_SOURCE_DIR}/flutter")
add_subdirectory(${FLUTTER_MANAGED_DIR})

# Application build; see runner/CMakeLists.txt.
add_subdirectory("runner")


# Generated plugin build rules, which manage building the plugins and adding
# them to the application.
include(flutter/generated_plugins.cmake)


# === Installation ===
# Support files are copied into place next to the executable, so that it can
# run in place. This is done instead of making a separate bundle (as on Linux)
# so that building and running from within Visual Studio will work.
set(BUILD_BUNDLE_DIR "$<TARGET_FILE_DIR:${BINARY_NAME}>")
# Make the "install" step default, as it's required to run.
set(CMAKE_VS_INCLUDE_INSTALL_TO_DEFAULT_BUILD 1)
if(CMAKE_INSTALL_PREFIX_INITIALIZED_TO_DEFAULT)
  set(CMAKE_INSTALL_PREFIX "${BUILD_BUNDLE_DIR}" CACHE PATH "..." FORCE)
endif()

set(INSTALL_BUNDLE_DATA_DIR "${CMAKE_INSTALL_PREFIX}/data")
set(INSTALL_BUNDLE_LIB_DIR "${CMAKE_INSTALL_PREFIX}")

install(TARGETS ${BINARY_NAME} RUNTIME DESTINATION "${CMAKE_INSTALL_PREFIX}"
  COMPONENT Runtime)

install(FILES "${FLUTTER_ICU_DATA_FILE}" DESTINATION "${INSTALL_BUNDLE_DATA_DIR}"
  COMPONENT Runtime)

install(FILES "${FLUTTER_LIBRARY}" DESTINATION "${INSTALL_BUNDLE_LIB_DIR}"
  COMPONENT Runtime)

if(PLUGIN_BUNDLED_LIBRARIES)
  install(FILES "${PLUGIN_BUNDLED_LIBRARIES}"
    DESTINATION "${INSTALL_BUNDLE_LIB_DIR}"
    COMPONENT Runtime)
endif()

# Copy the native assets provided by the build.dart from all packages.
set(NATIVE_ASSETS_DIR "${PROJECT_BUILD_DIR}native_assets/windows/")
install(DIRECTORY "${NATIVE_ASSETS_DIR}"
   DESTINATION "${INSTALL_BUNDLE_LIB_DIR}"
   COMPONENT Runtime)

# Fully re-copy the assets directory on each build to avoid having stale files
# from a previous install.
set(FLUTTER_ASSET_DIR_NAME "flutter_assets")
install(CODE "
  file(REMOVE_RECURSE \"${INSTALL_BUNDLE_DATA_DIR}/${FLUTTER_ASSET_DIR_NAME}\")
  " COMPONENT Runtime)
install(DIRECTORY "${PROJECT_BUILD_DIR}/${FLUTTER_ASSET_DIR_NAME}"
  DESTINATION "${INSTALL_BUNDLE_DATA_DIR}" COMPONENT Runtime)

# Install the AOT library on non-Debug builds only.
install(FILES "${AOT_LIBRARY}" DESTINATION "${INSTALL_BUNDLE_DATA_DIR}"
  CONFIGURATIONS Profile;Release
  COMPONENT Runtime)

</file>
<file path=".env.local.example">
SUPABASE_URL=https://your-project.supabase.co
SUPABASE_ANON_KEY=your-anon-key
EDAMAM_APP_ID=your-edamam-app-id
EDAMAM_APP_KEY=your-edamam-app-key
KROGER_API_KEY=your-kroger-key
GOOGLE_MAPS_API_KEY=your-maps-key
</file>
<file path=".flutter-plugins-dependencies">
{"info":"This is a generated file; do not edit or check into version control.","plugins":{"ios":[{"name":"app_links","path":"/Users/varyable/.pub-cache/hosted/pub.dev/app_links-6.4.0/","native_build":true,"dependencies":[],"dev_dependency":false},{"name":"connectivity_plus","path":"/Users/varyable/.pub-cache/hosted/pub.dev/connectivity_plus-5.0.2/","native_build":true,"dependencies":[],"dev_dependency":false},{"name":"flutter_native_splash","path":"/Users/varyable/.pub-cache/hosted/pub.dev/flutter_native_splash-2.4.6/","native_build":true,"dependencies":[],"dev_dependency":true},{"name":"flutter_secure_storage","path":"/Users/varyable/.pub-cache/hosted/pub.dev/flutter_secure_storage-9.2.4/","native_build":true,"dependencies":[],"dev_dependency":false},{"name":"google_maps_flutter_ios","path":"/Users/varyable/.pub-cache/hosted/pub.dev/google_maps_flutter_ios-2.15.4/","native_build":true,"dependencies":[],"dev_dependency":false},{"name":"path_provider_foundation","path":"/Users/varyable/.pub-cache/hosted/pub.dev/path_provider_foundation-2.4.1/","shared_darwin_source":true,"native_build":true,"dependencies":[],"dev_dependency":false},{"name":"shared_preferences_foundation","path":"/Users/varyable/.pub-cache/hosted/pub.dev/shared_preferences_foundation-2.5.4/","shared_darwin_source":true,"native_build":true,"dependencies":[],"dev_dependency":false},{"name":"sqflite_darwin","path":"/Users/varyable/.pub-cache/hosted/pub.dev/sqflite_darwin-2.4.2/","shared_darwin_source":true,"native_build":true,"dependencies":[],"dev_dependency":false},{"name":"url_launcher_ios","path":"/Users/varyable/.pub-cache/hosted/pub.dev/url_launcher_ios-6.3.3/","native_build":true,"dependencies":[],"dev_dependency":false}],"android":[{"name":"app_links","path":"/Users/varyable/.pub-cache/hosted/pub.dev/app_links-6.4.0/","native_build":true,"dependencies":[],"dev_dependency":false},{"name":"connectivity_plus","path":"/Users/varyable/.pub-cache/hosted/pub.dev/connectivity_plus-5.0.2/","native_build":true,"dependencies":[],"dev_dependency":false},{"name":"flutter_native_splash","path":"/Users/varyable/.pub-cache/hosted/pub.dev/flutter_native_splash-2.4.6/","native_build":true,"dependencies":[],"dev_dependency":true},{"name":"flutter_plugin_android_lifecycle","path":"/Users/varyable/.pub-cache/hosted/pub.dev/flutter_plugin_android_lifecycle-2.0.28/","native_build":true,"dependencies":[],"dev_dependency":false},{"name":"flutter_secure_storage","path":"/Users/varyable/.pub-cache/hosted/pub.dev/flutter_secure_storage-9.2.4/","native_build":true,"dependencies":[],"dev_dependency":false},{"name":"google_maps_flutter_android","path":"/Users/varyable/.pub-cache/hosted/pub.dev/google_maps_flutter_android-2.16.1/","native_build":true,"dependencies":["flutter_plugin_android_lifecycle"],"dev_dependency":false},{"name":"path_provider_android","path":"/Users/varyable/.pub-cache/hosted/pub.dev/path_provider_android-2.2.17/","native_build":true,"dependencies":[],"dev_dependency":false},{"name":"shared_preferences_android","path":"/Users/varyable/.pub-cache/hosted/pub.dev/shared_preferences_android-2.4.10/","native_build":true,"dependencies":[],"dev_dependency":false},{"name":"sqflite_android","path":"/Users/varyable/.pub-cache/hosted/pub.dev/sqflite_android-2.4.1/","native_build":true,"dependencies":[],"dev_dependency":false},{"name":"url_launcher_android","path":"/Users/varyable/.pub-cache/hosted/pub.dev/url_launcher_android-6.3.16/","native_build":true,"dependencies":[],"dev_dependency":false}],"macos":[{"name":"app_links","path":"/Users/varyable/.pub-cache/hosted/pub.dev/app_links-6.4.0/","native_build":true,"dependencies":[],"dev_dependency":false},{"name":"connectivity_plus","path":"/Users/varyable/.pub-cache/hosted/pub.dev/connectivity_plus-5.0.2/","native_build":true,"dependencies":[],"dev_dependency":false},{"name":"flutter_secure_storage_macos","path":"/Users/varyable/.pub-cache/hosted/pub.dev/flutter_secure_storage_macos-3.1.3/","native_build":true,"dependencies":[],"dev_dependency":false},{"name":"path_provider_foundation","path":"/Users/varyable/.pub-cache/hosted/pub.dev/path_provider_foundation-2.4.1/","shared_darwin_source":true,"native_build":true,"dependencies":[],"dev_dependency":false},{"name":"shared_preferences_foundation","path":"/Users/varyable/.pub-cache/hosted/pub.dev/shared_preferences_foundation-2.5.4/","shared_darwin_source":true,"native_build":true,"dependencies":[],"dev_dependency":false},{"name":"sqflite_darwin","path":"/Users/varyable/.pub-cache/hosted/pub.dev/sqflite_darwin-2.4.2/","shared_darwin_source":true,"native_build":true,"dependencies":[],"dev_dependency":false},{"name":"url_launcher_macos","path":"/Users/varyable/.pub-cache/hosted/pub.dev/url_launcher_macos-3.2.2/","native_build":true,"dependencies":[],"dev_dependency":false}],"linux":[{"name":"app_links_linux","path":"/Users/varyable/.pub-cache/hosted/pub.dev/app_links_linux-1.0.3/","native_build":false,"dependencies":["gtk"],"dev_dependency":false},{"name":"connectivity_plus","path":"/Users/varyable/.pub-cache/hosted/pub.dev/connectivity_plus-5.0.2/","native_build":false,"dependencies":[],"dev_dependency":false},{"name":"flutter_secure_storage_linux","path":"/Users/varyable/.pub-cache/hosted/pub.dev/flutter_secure_storage_linux-1.2.3/","native_build":true,"dependencies":[],"dev_dependency":false},{"name":"gtk","path":"/Users/varyable/.pub-cache/hosted/pub.dev/gtk-2.1.0/","native_build":true,"dependencies":[],"dev_dependency":false},{"name":"path_provider_linux","path":"/Users/varyable/.pub-cache/hosted/pub.dev/path_provider_linux-2.2.1/","native_build":false,"dependencies":[],"dev_dependency":false},{"name":"shared_preferences_linux","path":"/Users/varyable/.pub-cache/hosted/pub.dev/shared_preferences_linux-2.4.1/","native_build":false,"dependencies":["path_provider_linux"],"dev_dependency":false},{"name":"url_launcher_linux","path":"/Users/varyable/.pub-cache/hosted/pub.dev/url_launcher_linux-3.2.1/","native_build":true,"dependencies":[],"dev_dependency":false}],"windows":[{"name":"app_links","path":"/Users/varyable/.pub-cache/hosted/pub.dev/app_links-6.4.0/","native_build":true,"dependencies":[],"dev_dependency":false},{"name":"connectivity_plus","path":"/Users/varyable/.pub-cache/hosted/pub.dev/connectivity_plus-5.0.2/","native_build":true,"dependencies":[],"dev_dependency":false},{"name":"flutter_secure_storage_windows","path":"/Users/varyable/.pub-cache/hosted/pub.dev/flutter_secure_storage_windows-3.1.2/","native_build":true,"dependencies":[],"dev_dependency":false},{"name":"path_provider_windows","path":"/Users/varyable/.pub-cache/hosted/pub.dev/path_provider_windows-2.3.0/","native_build":false,"dependencies":[],"dev_dependency":false},{"name":"shared_preferences_windows","path":"/Users/varyable/.pub-cache/hosted/pub.dev/shared_preferences_windows-2.4.1/","native_build":false,"dependencies":["path_provider_windows"],"dev_dependency":false},{"name":"url_launcher_windows","path":"/Users/varyable/.pub-cache/hosted/pub.dev/url_launcher_windows-3.1.4/","native_build":true,"dependencies":[],"dev_dependency":false}],"web":[{"name":"app_links_web","path":"/Users/varyable/.pub-cache/hosted/pub.dev/app_links_web-1.0.4/","dependencies":[],"dev_dependency":false},{"name":"connectivity_plus","path":"/Users/varyable/.pub-cache/hosted/pub.dev/connectivity_plus-5.0.2/","dependencies":[],"dev_dependency":false},{"name":"flutter_native_splash","path":"/Users/varyable/.pub-cache/hosted/pub.dev/flutter_native_splash-2.4.6/","dependencies":[],"dev_dependency":true},{"name":"flutter_secure_storage_web","path":"/Users/varyable/.pub-cache/hosted/pub.dev/flutter_secure_storage_web-1.2.1/","dependencies":[],"dev_dependency":false},{"name":"google_maps_flutter_web","path":"/Users/varyable/.pub-cache/hosted/pub.dev/google_maps_flutter_web-0.5.12+1/","dependencies":[],"dev_dependency":false},{"name":"shared_preferences_web","path":"/Users/varyable/.pub-cache/hosted/pub.dev/shared_preferences_web-2.4.3/","dependencies":[],"dev_dependency":false},{"name":"url_launcher_web","path":"/Users/varyable/.pub-cache/hosted/pub.dev/url_launcher_web-2.4.1/","dependencies":[],"dev_dependency":false}]},"dependencyGraph":[{"name":"app_links","dependencies":["app_links_linux","app_links_web"]},{"name":"app_links_linux","dependencies":["gtk"]},{"name":"app_links_web","dependencies":[]},{"name":"connectivity_plus","dependencies":[]},{"name":"flutter_native_splash","dependencies":[]},{"name":"flutter_plugin_android_lifecycle","dependencies":[]},{"name":"flutter_secure_storage","dependencies":["flutter_secure_storage_linux","flutter_secure_storage_macos","flutter_secure_storage_web","flutter_secure_storage_windows"]},{"name":"flutter_secure_storage_linux","dependencies":[]},{"name":"flutter_secure_storage_macos","dependencies":[]},{"name":"flutter_secure_storage_web","dependencies":[]},{"name":"flutter_secure_storage_windows","dependencies":["path_provider"]},{"name":"google_maps_flutter","dependencies":["google_maps_flutter_android","google_maps_flutter_ios","google_maps_flutter_web"]},{"name":"google_maps_flutter_android","dependencies":["flutter_plugin_android_lifecycle"]},{"name":"google_maps_flutter_ios","dependencies":[]},{"name":"google_maps_flutter_web","dependencies":[]},{"name":"gtk","dependencies":[]},{"name":"path_provider","dependencies":["path_provider_android","path_provider_foundation","path_provider_linux","path_provider_windows"]},{"name":"path_provider_android","dependencies":[]},{"name":"path_provider_foundation","dependencies":[]},{"name":"path_provider_linux","dependencies":[]},{"name":"path_provider_windows","dependencies":[]},{"name":"shared_preferences","dependencies":["shared_preferences_android","shared_preferences_foundation","shared_preferences_linux","shared_preferences_web","shared_preferences_windows"]},{"name":"shared_preferences_android","dependencies":[]},{"name":"shared_preferences_foundation","dependencies":[]},{"name":"shared_preferences_linux","dependencies":["path_provider_linux"]},{"name":"shared_preferences_web","dependencies":[]},{"name":"shared_preferences_windows","dependencies":["path_provider_windows"]},{"name":"sqflite","dependencies":["sqflite_android","sqflite_darwin"]},{"name":"sqflite_android","dependencies":[]},{"name":"sqflite_darwin","dependencies":[]},{"name":"url_launcher","dependencies":["url_launcher_android","url_launcher_ios","url_launcher_linux","url_launcher_macos","url_launcher_web","url_launcher_windows"]},{"name":"url_launcher_android","dependencies":[]},{"name":"url_launcher_ios","dependencies":[]},{"name":"url_launcher_linux","dependencies":[]},{"name":"url_launcher_macos","dependencies":[]},{"name":"url_launcher_web","dependencies":[]},{"name":"url_launcher_windows","dependencies":[]}],"date_created":"2025-06-25 12:34:28.362231","version":"3.32.4","swift_package_manager_enabled":{"ios":false,"macos":false}}
</file>
<file path=".gitignore">
# Miscellaneous
*.class
*.log
*.pyc
*.swp
.DS_Store
.atom/
.build/
.buildlog/
.history
.svn/
.swiftpm/
migrate_working_dir/

# IntelliJ related
*.iml
*.ipr
*.iws
.idea/

# The .vscode folder contains launch configuration and tasks you configure in
# VS Code which you may wish to be included in version control, so this line
# is commented out by default.
#.vscode/

# Flutter/Dart/Pub related
**/doc/api/
**/ios/Flutter/.last_build_id
.dart_tool/
.flutter-plugins
.flutter-plugins-dependencies
.packages
.pub-cache/
.pub/
/build/
ios/Pods/
android/.gradle/
.env
.env.local

# Symbolication related
app.*.symbols

# Obfuscation related
app.*.map.json

# Android Studio will place build artifacts here
/android/app/debug
/android/app/profile
/android/app/release

# Node/Supabase Functions
node_modules/
functions/node_modules/
functions/.env
</file>
<file path=".metadata">
# This file tracks properties of this Flutter project.
# Used by Flutter tool to assess capabilities and perform upgrades etc.
#
# This file should be version controlled and should not be manually edited.

version:
  revision: "6fba2447e95c451518584c35e25f5433f14d888c"
  channel: "stable"

project_type: app

# Tracks metadata for the flutter migrate command
migration:
  platforms:
    - platform: root
      create_revision: 6fba2447e95c451518584c35e25f5433f14d888c
      base_revision: 6fba2447e95c451518584c35e25f5433f14d888c
    - platform: android
      create_revision: 6fba2447e95c451518584c35e25f5433f14d888c
      base_revision: 6fba2447e95c451518584c35e25f5433f14d888c
    - platform: ios
      create_revision: 6fba2447e95c451518584c35e25f5433f14d888c
      base_revision: 6fba2447e95c451518584c35e25f5433f14d888c
    - platform: linux
      create_revision: 6fba2447e95c451518584c35e25f5433f14d888c
      base_revision: 6fba2447e95c451518584c35e25f5433f14d888c
    - platform: macos
      create_revision: 6fba2447e95c451518584c35e25f5433f14d888c
      base_revision: 6fba2447e95c451518584c35e25f5433f14d888c
    - platform: web
      create_revision: 6fba2447e95c451518584c35e25f5433f14d888c
      base_revision: 6fba2447e95c451518584c35e25f5433f14d888c
    - platform: windows
      create_revision: 6fba2447e95c451518584c35e25f5433f14d888c
      base_revision: 6fba2447e95c451518584c35e25f5433f14d888c

  # User provided section

  # List of Local paths (relative to this file) that should be
  # ignored by the migrate tool.
  #
  # Files that are not part of the templates will be ignored by default.
  unmanaged_files:
    - 'lib/main.dart'
    - 'ios/Runner.xcodeproj/project.pbxproj'

</file>
<file path="analysis_options.yaml">
# This file configures the analyzer, which statically analyzes Dart code to
# check for errors, warnings, and lints.
#
# The issues identified by the analyzer are surfaced in the UI of Dart-enabled
# IDEs (https://dart.dev/tools#ides-and-editors). The analyzer can also be
# invoked from the command line by running `flutter analyze`.

# The following line activates a set of recommended lints for Flutter apps,
# packages, and plugins designed to encourage good coding practices.
include: package:flutter_lints/flutter.yaml

linter:
  # The lint rules applied to this project can be customized in the
  # section below to disable rules from the `package:flutter_lints/flutter.yaml`
  # included above or to enable additional rules. A list of all available lints
  # and their documentation is published at https://dart.dev/lints.
  #
  # Instead of disabling a lint rule for the entire project in the
  # section below, it can also be suppressed for a single line of code
  # or a specific dart file by using the `// ignore: name_of_lint` and
  # `// ignore_for_file: name_of_lint` syntax on the line or in the file
  # producing the lint.
  rules:
    # avoid_print: false  # Uncomment to disable the `avoid_print` rule
    # prefer_single_quotes: true  # Uncomment to enable the `prefer_single_quotes` rule

# Additional information about this file can be found at
# https://dart.dev/guides/language/analysis-options

</file>
<file path="devtools_options.yaml">
description: This file stores settings for Dart & Flutter DevTools.
documentation: https://docs.flutter.dev/tools/devtools/extensions#configure-extension-enablement-states
extensions:

</file>
<file path="foodster.iml">
<?xml version="1.0" encoding="UTF-8"?>
<module type="JAVA_MODULE" version="4">
  <component name="NewModuleRootManager" inherit-compiler-output="true">
    <exclude-output />
    <content url="file://$MODULE_DIR$">
      <sourceFolder url="file://$MODULE_DIR$/lib" isTestSource="false" />
      <sourceFolder url="file://$MODULE_DIR$/test" isTestSource="true" />
      <excludeFolder url="file://$MODULE_DIR$/.dart_tool" />
      <excludeFolder url="file://$MODULE_DIR$/.idea" />
      <excludeFolder url="file://$MODULE_DIR$/build" />
    </content>
    <orderEntry type="sourceFolder" forTests="false" />
    <orderEntry type="library" name="Dart SDK" level="project" />
    <orderEntry type="library" name="Flutter Plugins" level="project" />
    <orderEntry type="library" name="Dart Packages" level="project" />
  </component>
</module>

</file>
<file path="pubspec.lock">
# Generated by pub
# See https://dart.dev/tools/pub/glossary#lockfile
packages:
  _fe_analyzer_shared:
    dependency: transitive
    description:
      name: _fe_analyzer_shared
      sha256: e55636ed79578b9abca5fecf9437947798f5ef7456308b5cb85720b793eac92f
      url: "https://pub.dev"
    source: hosted
    version: "82.0.0"
  analyzer:
    dependency: transitive
    description:
      name: analyzer
      sha256: "904ae5bb474d32c38fb9482e2d925d5454cda04ddd0e55d2e6826bc72f6ba8c0"
      url: "https://pub.dev"
    source: hosted
    version: "7.4.5"
  ansicolor:
    dependency: transitive
    description:
      name: ansicolor
      sha256: "50e982d500bc863e1d703448afdbf9e5a72eb48840a4f766fa361ffd6877055f"
      url: "https://pub.dev"
    source: hosted
    version: "2.0.3"
  app_links:
    dependency: transitive
    description:
      name: app_links
      sha256: "85ed8fc1d25a76475914fff28cc994653bd900bc2c26e4b57a49e097febb54ba"
      url: "https://pub.dev"
    source: hosted
    version: "6.4.0"
  app_links_linux:
    dependency: transitive
    description:
      name: app_links_linux
      sha256: f5f7173a78609f3dfd4c2ff2c95bd559ab43c80a87dc6a095921d96c05688c81
      url: "https://pub.dev"
    source: hosted
    version: "1.0.3"
  app_links_platform_interface:
    dependency: transitive
    description:
      name: app_links_platform_interface
      sha256: "05f5379577c513b534a29ddea68176a4d4802c46180ee8e2e966257158772a3f"
      url: "https://pub.dev"
    source: hosted
    version: "2.0.2"
  app_links_web:
    dependency: transitive
    description:
      name: app_links_web
      sha256: af060ed76183f9e2b87510a9480e56a5352b6c249778d07bd2c95fc35632a555
      url: "https://pub.dev"
    source: hosted
    version: "1.0.4"
  archive:
    dependency: transitive
    description:
      name: archive
      sha256: "2fde1607386ab523f7a36bb3e7edb43bd58e6edaf2ffb29d8a6d578b297fdbbd"
      url: "https://pub.dev"
    source: hosted
    version: "4.0.7"
  args:
    dependency: transitive
    description:
      name: args
      sha256: d0481093c50b1da8910eb0bb301626d4d8eb7284aa739614d2b394ee09e3ea04
      url: "https://pub.dev"
    source: hosted
    version: "2.7.0"
  async:
    dependency: transitive
    description:
      name: async
      sha256: "758e6d74e971c3e5aceb4110bfd6698efc7f501675bcfe0c775459a8140750eb"
      url: "https://pub.dev"
    source: hosted
    version: "2.13.0"
  bloc:
    dependency: transitive
    description:
      name: bloc
      sha256: "106842ad6569f0b60297619e9e0b1885c2fb9bf84812935490e6c5275777804e"
      url: "https://pub.dev"
    source: hosted
    version: "8.1.4"
  bloc_test:
    dependency: "direct dev"
    description:
      name: bloc_test
      sha256: "165a6ec950d9252ebe36dc5335f2e6eb13055f33d56db0eeb7642768849b43d2"
      url: "https://pub.dev"
    source: hosted
    version: "9.1.7"
  boolean_selector:
    dependency: transitive
    description:
      name: boolean_selector
      sha256: "8aab1771e1243a5063b8b0ff68042d67334e3feab9e95b9490f9a6ebf73b42ea"
      url: "https://pub.dev"
    source: hosted
    version: "2.1.2"
  build:
    dependency: transitive
    description:
      name: build
      sha256: "74273591bd8b7f82eeb1f191c1b65a6576535bbfd5ca3722778b07d5702d33cc"
      url: "https://pub.dev"
    source: hosted
    version: "2.5.3"
  build_config:
    dependency: transitive
    description:
      name: build_config
      sha256: "4ae2de3e1e67ea270081eaee972e1bd8f027d459f249e0f1186730784c2e7e33"
      url: "https://pub.dev"
    source: hosted
    version: "1.1.2"
  build_daemon:
    dependency: transitive
    description:
      name: build_daemon
      sha256: "8e928697a82be082206edb0b9c99c5a4ad6bc31c9e9b8b2f291ae65cd4a25daa"
      url: "https://pub.dev"
    source: hosted
    version: "4.0.4"
  build_resolvers:
    dependency: transitive
    description:
      name: build_resolvers
      sha256: badce70566085f2e87434531c4a6bc8e833672f755fc51146d612245947e91c9
      url: "https://pub.dev"
    source: hosted
    version: "2.5.3"
  build_runner:
    dependency: "direct dev"
    description:
      name: build_runner
      sha256: b9070a4127033777c0e63195f6f117ed16a351ed676f6313b095cf4f328c0b82
      url: "https://pub.dev"
    source: hosted
    version: "2.5.3"
  build_runner_core:
    dependency: transitive
    description:
      name: build_runner_core
      sha256: "1cdfece3eeb3f1263f7dbf5bcc0cba697bd0c22d2c866cb4b578c954dbb09bcf"
      url: "https://pub.dev"
    source: hosted
    version: "9.1.1"
  built_collection:
    dependency: transitive
    description:
      name: built_collection
      sha256: "376e3dd27b51ea877c28d525560790aee2e6fbb5f20e2f85d5081027d94e2100"
      url: "https://pub.dev"
    source: hosted
    version: "5.1.1"
  built_value:
    dependency: transitive
    description:
      name: built_value
      sha256: "082001b5c3dc495d4a42f1d5789990505df20d8547d42507c29050af6933ee27"
      url: "https://pub.dev"
    source: hosted
    version: "8.10.1"
  cached_network_image:
    dependency: "direct main"
    description:
      name: cached_network_image
      sha256: "7c1183e361e5c8b0a0f21a28401eecdbde252441106a9816400dd4c2b2424916"
      url: "https://pub.dev"
    source: hosted
    version: "3.4.1"
  cached_network_image_platform_interface:
    dependency: transitive
    description:
      name: cached_network_image_platform_interface
      sha256: "35814b016e37fbdc91f7ae18c8caf49ba5c88501813f73ce8a07027a395e2829"
      url: "https://pub.dev"
    source: hosted
    version: "4.1.1"
  cached_network_image_web:
    dependency: transitive
    description:
      name: cached_network_image_web
      sha256: "980842f4e8e2535b8dbd3d5ca0b1f0ba66bf61d14cc3a17a9b4788a3685ba062"
      url: "https://pub.dev"
    source: hosted
    version: "1.3.1"
  characters:
    dependency: transitive
    description:
      name: characters
      sha256: f71061c654a3380576a52b451dd5532377954cf9dbd272a78fc8479606670803
      url: "https://pub.dev"
    source: hosted
    version: "1.4.0"
  checked_yaml:
    dependency: transitive
    description:
      name: checked_yaml
      sha256: "959525d3162f249993882720d52b7e0c833978df229be20702b33d48d91de70f"
      url: "https://pub.dev"
    source: hosted
    version: "2.0.4"
  cli_config:
    dependency: transitive
    description:
      name: cli_config
      sha256: ac20a183a07002b700f0c25e61b7ee46b23c309d76ab7b7640a028f18e4d99ec
      url: "https://pub.dev"
    source: hosted
    version: "0.2.0"
  cli_util:
    dependency: transitive
    description:
      name: cli_util
      sha256: ff6785f7e9e3c38ac98b2fb035701789de90154024a75b6cb926445e83197d1c
      url: "https://pub.dev"
    source: hosted
    version: "0.4.2"
  clock:
    dependency: transitive
    description:
      name: clock
      sha256: fddb70d9b5277016c77a80201021d40a2247104d9f4aa7bab7157b7e3f05b84b
      url: "https://pub.dev"
    source: hosted
    version: "1.1.2"
  code_builder:
    dependency: transitive
    description:
      name: code_builder
      sha256: "0ec10bf4a89e4c613960bf1e8b42c64127021740fb21640c29c909826a5eea3e"
      url: "https://pub.dev"
    source: hosted
    version: "4.10.1"
  collection:
    dependency: transitive
    description:
      name: collection
      sha256: "2f5709ae4d3d59dd8f7cd309b4e023046b57d8a6c82130785d2b0e5868084e76"
      url: "https://pub.dev"
    source: hosted
    version: "1.19.1"
  connectivity_plus:
    dependency: "direct main"
    description:
      name: connectivity_plus
      sha256: "224a77051d52a11fbad53dd57827594d3bd24f945af28bd70bab376d68d437f0"
      url: "https://pub.dev"
    source: hosted
    version: "5.0.2"
  connectivity_plus_platform_interface:
    dependency: transitive
    description:
      name: connectivity_plus_platform_interface
      sha256: cf1d1c28f4416f8c654d7dc3cd638ec586076255d407cef3ddbdaf178272a71a
      url: "https://pub.dev"
    source: hosted
    version: "1.2.4"
  convert:
    dependency: transitive
    description:
      name: convert
      sha256: b30acd5944035672bc15c6b7a8b47d773e41e2f17de064350988c5d02adb1c68
      url: "https://pub.dev"
    source: hosted
    version: "3.1.2"
  coverage:
    dependency: transitive
    description:
      name: coverage
      sha256: aa07dbe5f2294c827b7edb9a87bba44a9c15a3cc81bc8da2ca19b37322d30080
      url: "https://pub.dev"
    source: hosted
    version: "1.14.1"
  crypto:
    dependency: transitive
    description:
      name: crypto
      sha256: "1e445881f28f22d6140f181e07737b22f1e099a5e1ff94b0af2f9e4a463f4855"
      url: "https://pub.dev"
    source: hosted
    version: "3.0.6"
  csslib:
    dependency: transitive
    description:
      name: csslib
      sha256: "09bad715f418841f976c77db72d5398dc1253c21fb9c0c7f0b0b985860b2d58e"
      url: "https://pub.dev"
    source: hosted
    version: "1.0.2"
  cupertino_icons:
    dependency: "direct main"
    description:
      name: cupertino_icons
      sha256: ba631d1c7f7bef6b729a622b7b752645a2d076dba9976925b8f25725a30e1ee6
      url: "https://pub.dev"
    source: hosted
    version: "1.0.8"
  dap:
    dependency: transitive
    description:
      name: dap
      sha256: "42b0b083a09c59a118741769e218fc3738980ab591114f09d1026241d2b9c290"
      url: "https://pub.dev"
    source: hosted
    version: "1.4.0"
  dart_mcp:
    dependency: transitive
    description:
      name: dart_mcp
      sha256: "7caf75ac8040d146e49f9987a7b2659afdb90b9246509c522f00bdcbfcda07ff"
      url: "https://pub.dev"
    source: hosted
    version: "0.2.2"
  dart_mcp_server:
    dependency: "direct dev"
    description:
      path: "pkgs/dart_mcp_server"
      ref: main
      resolved-ref: "12ac0a4099baf6c9b9567ffedb2b7c368938bca4"
      url: "https://github.com/dart-lang/ai.git"
    source: git
    version: "0.0.0"
  dart_style:
    dependency: transitive
    description:
      name: dart_style
      sha256: "5b236382b47ee411741447c1f1e111459c941ea1b3f2b540dde54c210a3662af"
      url: "https://pub.dev"
    source: hosted
    version: "3.1.0"
  dartz:
    dependency: "direct main"
    description:
      name: dartz
      sha256: e6acf34ad2e31b1eb00948692468c30ab48ac8250e0f0df661e29f12dd252168
      url: "https://pub.dev"
    source: hosted
    version: "0.10.1"
  dbus:
    dependency: transitive
    description:
      name: dbus
      sha256: "79e0c23480ff85dc68de79e2cd6334add97e48f7f4865d17686dd6ea81a47e8c"
      url: "https://pub.dev"
    source: hosted
    version: "0.7.11"
  dds_service_extensions:
    dependency: transitive
    description:
      name: dds_service_extensions
      sha256: c514114300ab30a95903fed1fdcf2949d057a0ea961168ec890a2b415b3ec52a
      url: "https://pub.dev"
    source: hosted
    version: "2.0.2"
  devtools_shared:
    dependency: transitive
    description:
      name: devtools_shared
      sha256: "659e2d65aa5ef5c3551163811c5c6fa1b973b3df80d8cac6f618035edcdc1096"
      url: "https://pub.dev"
    source: hosted
    version: "11.2.1"
  diff_match_patch:
    dependency: transitive
    description:
      name: diff_match_patch
      sha256: "2efc9e6e8f449d0abe15be240e2c2a3bcd977c8d126cfd70598aee60af35c0a4"
      url: "https://pub.dev"
    source: hosted
    version: "0.4.1"
  dio:
    dependency: "direct main"
    description:
      name: dio
      sha256: "253a18bbd4851fecba42f7343a1df3a9a4c1d31a2c1b37e221086b4fa8c8dbc9"
      url: "https://pub.dev"
    source: hosted
    version: "5.8.0+1"
  dio_web_adapter:
    dependency: transitive
    description:
      name: dio_web_adapter
      sha256: "7586e476d70caecaf1686d21eee7247ea43ef5c345eab9e0cc3583ff13378d78"
      url: "https://pub.dev"
    source: hosted
    version: "2.1.1"
  dtd:
    dependency: transitive
    description:
      name: dtd
      sha256: "14a0360d898ded87c3d99591fc386b8a6ea5d432927bee709b22130cd25b993a"
      url: "https://pub.dev"
    source: hosted
    version: "2.5.1"
  equatable:
    dependency: "direct main"
    description:
      name: equatable
      sha256: "567c64b3cb4cf82397aac55f4f0cbd3ca20d77c6c03bedbc4ceaddc08904aef7"
      url: "https://pub.dev"
    source: hosted
    version: "2.0.7"
  extension_discovery:
    dependency: transitive
    description:
      name: extension_discovery
      sha256: de1fce715ab013cdfb00befc3bdf0914bea5e409c3a567b7f8f144bc061611a7
      url: "https://pub.dev"
    source: hosted
    version: "2.1.0"
  fake_async:
    dependency: transitive
    description:
      name: fake_async
      sha256: "5368f224a74523e8d2e7399ea1638b37aecfca824a3cc4dfdf77bf1fa905ac44"
      url: "https://pub.dev"
    source: hosted
    version: "1.3.3"
  ffi:
    dependency: transitive
    description:
      name: ffi
      sha256: "289279317b4b16eb2bb7e271abccd4bf84ec9bdcbe999e278a94b804f5630418"
      url: "https://pub.dev"
    source: hosted
    version: "2.1.4"
  file:
    dependency: transitive
    description:
      name: file
      sha256: a3b4f84adafef897088c160faf7dfffb7696046cb13ae90b508c2cbc95d3b8d4
      url: "https://pub.dev"
    source: hosted
    version: "7.0.1"
  fixnum:
    dependency: transitive
    description:
      name: fixnum
      sha256: b6dc7065e46c974bc7c5f143080a6764ec7a4be6da1285ececdc37be96de53be
      url: "https://pub.dev"
    source: hosted
    version: "1.1.1"
  fl_chart:
    dependency: "direct main"
    description:
      name: fl_chart
      sha256: "00b74ae680df6b1135bdbea00a7d1fc072a9180b7c3f3702e4b19a9943f5ed7d"
      url: "https://pub.dev"
    source: hosted
    version: "0.66.2"
  flutter:
    dependency: "direct main"
    description: flutter
    source: sdk
    version: "0.0.0"
  flutter_bloc:
    dependency: "direct main"
    description:
      name: flutter_bloc
      sha256: b594505eac31a0518bdcb4b5b79573b8d9117b193cc80cc12e17d639b10aa27a
      url: "https://pub.dev"
    source: hosted
    version: "8.1.6"
  flutter_cache_manager:
    dependency: transitive
    description:
      name: flutter_cache_manager
      sha256: "400b6592f16a4409a7f2bb929a9a7e38c72cceb8ffb99ee57bbf2cb2cecf8386"
      url: "https://pub.dev"
    source: hosted
    version: "3.4.1"
  flutter_dotenv:
    dependency: "direct main"
    description:
      name: flutter_dotenv
      sha256: b7c7be5cd9f6ef7a78429cabd2774d3c4af50e79cb2b7593e3d5d763ef95c61b
      url: "https://pub.dev"
    source: hosted
    version: "5.2.1"
  flutter_launcher_icons:
    dependency: "direct dev"
    description:
      name: flutter_launcher_icons
      sha256: "526faf84284b86a4cb36d20a5e45147747b7563d921373d4ee0559c54fcdbcea"
      url: "https://pub.dev"
    source: hosted
    version: "0.13.1"
  flutter_lints:
    dependency: "direct dev"
    description:
      name: flutter_lints
      sha256: "5398f14efa795ffb7a33e9b6a08798b26a180edac4ad7db3f231e40f82ce11e1"
      url: "https://pub.dev"
    source: hosted
    version: "5.0.0"
  flutter_localizations:
    dependency: "direct main"
    description: flutter
    source: sdk
    version: "0.0.0"
  flutter_native_splash:
    dependency: "direct dev"
    description:
      name: flutter_native_splash
      sha256: "8321a6d11a8d13977fa780c89de8d257cce3d841eecfb7a4cadffcc4f12d82dc"
      url: "https://pub.dev"
    source: hosted
    version: "2.4.6"
  flutter_plugin_android_lifecycle:
    dependency: transitive
    description:
      name: flutter_plugin_android_lifecycle
      sha256: f948e346c12f8d5480d2825e03de228d0eb8c3a737e4cdaa122267b89c022b5e
      url: "https://pub.dev"
    source: hosted
    version: "2.0.28"
  flutter_secure_storage:
    dependency: "direct main"
    description:
      name: flutter_secure_storage
      sha256: "9cad52d75ebc511adfae3d447d5d13da15a55a92c9410e50f67335b6d21d16ea"
      url: "https://pub.dev"
    source: hosted
    version: "9.2.4"
  flutter_secure_storage_linux:
    dependency: transitive
    description:
      name: flutter_secure_storage_linux
      sha256: be76c1d24a97d0b98f8b54bce6b481a380a6590df992d0098f868ad54dc8f688
      url: "https://pub.dev"
    source: hosted
    version: "1.2.3"
  flutter_secure_storage_macos:
    dependency: transitive
    description:
      name: flutter_secure_storage_macos
      sha256: "6c0a2795a2d1de26ae202a0d78527d163f4acbb11cde4c75c670f3a0fc064247"
      url: "https://pub.dev"
    source: hosted
    version: "3.1.3"
  flutter_secure_storage_platform_interface:
    dependency: transitive
    description:
      name: flutter_secure_storage_platform_interface
      sha256: cf91ad32ce5adef6fba4d736a542baca9daf3beac4db2d04be350b87f69ac4a8
      url: "https://pub.dev"
    source: hosted
    version: "1.1.2"
  flutter_secure_storage_web:
    dependency: transitive
    description:
      name: flutter_secure_storage_web
      sha256: f4ebff989b4f07b2656fb16b47852c0aab9fed9b4ec1c70103368337bc1886a9
      url: "https://pub.dev"
    source: hosted
    version: "1.2.1"
  flutter_secure_storage_windows:
    dependency: transitive
    description:
      name: flutter_secure_storage_windows
      sha256: b20b07cb5ed4ed74fc567b78a72936203f587eba460af1df11281c9326cd3709
      url: "https://pub.dev"
    source: hosted
    version: "3.1.2"
  flutter_svg:
    dependency: "direct main"
    description:
      name: flutter_svg
      sha256: cd57f7969b4679317c17af6fd16ee233c1e60a82ed209d8a475c54fd6fd6f845
      url: "https://pub.dev"
    source: hosted
    version: "2.2.0"
  flutter_test:
    dependency: "direct dev"
    description: flutter
    source: sdk
    version: "0.0.0"
  flutter_web_plugins:
    dependency: transitive
    description: flutter
    source: sdk
    version: "0.0.0"
  frontend_server_client:
    dependency: transitive
    description:
      name: frontend_server_client
      sha256: f64a0333a82f30b0cca061bc3d143813a486dc086b574bfb233b7c1372427694
      url: "https://pub.dev"
    source: hosted
    version: "4.0.0"
  functions_client:
    dependency: transitive
    description:
      name: functions_client
      sha256: "91bd57c5ee843957bfee68fdcd7a2e8b3c1081d448e945d33ff695fb9c2a686c"
      url: "https://pub.dev"
    source: hosted
    version: "2.4.3"
  get_it:
    dependency: "direct main"
    description:
      name: get_it
      sha256: d85128a5dae4ea777324730dc65edd9c9f43155c109d5cc0a69cab74139fbac1
      url: "https://pub.dev"
    source: hosted
    version: "7.7.0"
  glob:
    dependency: transitive
    description:
      name: glob
      sha256: c3f1ee72c96f8f78935e18aa8cecced9ab132419e8625dc187e1c2408efc20de
      url: "https://pub.dev"
    source: hosted
    version: "2.1.3"
  google_fonts:
    dependency: "direct main"
    description:
      name: google_fonts
      sha256: b1ac0fe2832c9cc95e5e88b57d627c5e68c223b9657f4b96e1487aa9098c7b82
      url: "https://pub.dev"
    source: hosted
    version: "6.2.1"
  google_maps:
    dependency: transitive
    description:
      name: google_maps
      sha256: "4d6e199c561ca06792c964fa24b2bac7197bf4b401c2e1d23e345e5f9939f531"
      url: "https://pub.dev"
    source: hosted
    version: "8.1.1"
  google_maps_flutter:
    dependency: "direct main"
    description:
      name: google_maps_flutter
      sha256: e1805e5a5885bd14a1c407c59229f478af169bf4d04388586b19f53145a5db3a
      url: "https://pub.dev"
    source: hosted
    version: "2.12.3"
  google_maps_flutter_android:
    dependency: transitive
    description:
      name: google_maps_flutter_android
      sha256: ab83128296fbeaa52e8f2b3bf53bcd895e64778edddcdc07bc8f33f4ea78076c
      url: "https://pub.dev"
    source: hosted
    version: "2.16.1"
  google_maps_flutter_ios:
    dependency: transitive
    description:
      name: google_maps_flutter_ios
      sha256: d03678415da9de8ce7208c674b264fc75946f326e696b4b7f84c80920fc58df6
      url: "https://pub.dev"
    source: hosted
    version: "2.15.4"
  google_maps_flutter_platform_interface:
    dependency: transitive
    description:
      name: google_maps_flutter_platform_interface
      sha256: f8293f072ed8b068b092920a72da6693aa8b3d62dc6b5c5f0bc44c969a8a776c
      url: "https://pub.dev"
    source: hosted
    version: "2.12.1"
  google_maps_flutter_web:
    dependency: transitive
    description:
      name: google_maps_flutter_web
      sha256: a9822dbf31a3f76f239f6bda346511b051d3edf739806e9091be2729e8b645de
      url: "https://pub.dev"
    source: hosted
    version: "0.5.12+1"
  gotrue:
    dependency: transitive
    description:
      name: gotrue
      sha256: "941694654ab659990547798569771d8d092f2ade84a72e75bb9bbca249f3d3b1"
      url: "https://pub.dev"
    source: hosted
    version: "2.13.0"
  graphs:
    dependency: transitive
    description:
      name: graphs
      sha256: "741bbf84165310a68ff28fe9e727332eef1407342fca52759cb21ad8177bb8d0"
      url: "https://pub.dev"
    source: hosted
    version: "2.3.2"
  gtk:
    dependency: transitive
    description:
      name: gtk
      sha256: e8ce9ca4b1df106e4d72dad201d345ea1a036cc12c360f1a7d5a758f78ffa42c
      url: "https://pub.dev"
    source: hosted
    version: "2.1.0"
  hive:
    dependency: transitive
    description:
      name: hive
      sha256: "8dcf6db979d7933da8217edcec84e9df1bdb4e4edc7fc77dbd5aa74356d6d941"
      url: "https://pub.dev"
    source: hosted
    version: "2.2.3"
  hive_flutter:
    dependency: "direct main"
    description:
      name: hive_flutter
      sha256: dca1da446b1d808a51689fb5d0c6c9510c0a2ba01e22805d492c73b68e33eecc
      url: "https://pub.dev"
    source: hosted
    version: "1.1.0"
  html:
    dependency: transitive
    description:
      name: html
      sha256: "6d1264f2dffa1b1101c25a91dff0dc2daee4c18e87cd8538729773c073dbf602"
      url: "https://pub.dev"
    source: hosted
    version: "0.15.6"
  http:
    dependency: "direct main"
    description:
      name: http
      sha256: "2c11f3f94c687ee9bad77c171151672986360b2b001d109814ee7140b2cf261b"
      url: "https://pub.dev"
    source: hosted
    version: "1.4.0"
  http_multi_server:
    dependency: transitive
    description:
      name: http_multi_server
      sha256: aa6199f908078bb1c5efb8d8638d4ae191aac11b311132c3ef48ce352fb52ef8
      url: "https://pub.dev"
    source: hosted
    version: "3.2.2"
  http_parser:
    dependency: transitive
    description:
      name: http_parser
      sha256: "178d74305e7866013777bab2c3d8726205dc5a4dd935297175b19a23a2e66571"
      url: "https://pub.dev"
    source: hosted
    version: "4.1.2"
  image:
    dependency: transitive
    description:
      name: image
      sha256: "4e973fcf4caae1a4be2fa0a13157aa38a8f9cb049db6529aa00b4d71abc4d928"
      url: "https://pub.dev"
    source: hosted
    version: "4.5.4"
  injectable:
    dependency: "direct main"
    description:
      name: injectable
      sha256: "5e1556ea1d374fe44cbe846414d9bab346285d3d8a1da5877c01ad0774006068"
      url: "https://pub.dev"
    source: hosted
    version: "2.5.0"
  injectable_generator:
    dependency: "direct dev"
    description:
      name: injectable_generator
      sha256: b04673a4c88b3a848c0c77bf58b8309f9b9e064d9fe1df5450c8ee1675eaea1a
      url: "https://pub.dev"
    source: hosted
    version: "2.7.0"
  intl:
    dependency: "direct main"
    description:
      name: intl
      sha256: "3df61194eb431efc39c4ceba583b95633a403f46c9fd341e550ce0bfa50e9aa5"
      url: "https://pub.dev"
    source: hosted
    version: "0.20.2"
  io:
    dependency: transitive
    description:
      name: io
      sha256: dfd5a80599cf0165756e3181807ed3e77daf6dd4137caaad72d0b7931597650b
      url: "https://pub.dev"
    source: hosted
    version: "1.0.5"
  js:
    dependency: transitive
    description:
      name: js
      sha256: f2c445dce49627136094980615a031419f7f3eb393237e4ecd97ac15dea343f3
      url: "https://pub.dev"
    source: hosted
    version: "0.6.7"
  json_annotation:
    dependency: transitive
    description:
      name: json_annotation
      sha256: "1ce844379ca14835a50d2f019a3099f419082cfdd231cd86a142af94dd5c6bb1"
      url: "https://pub.dev"
    source: hosted
    version: "4.9.0"
  json_rpc_2:
    dependency: transitive
    description:
      name: json_rpc_2
      sha256: "246b321532f0e8e2ba474b4d757eaa558ae4fdd0688fdbc1e1ca9705f9b8ca0e"
      url: "https://pub.dev"
    source: hosted
    version: "3.0.3"
  jwt_decode:
    dependency: transitive
    description:
      name: jwt_decode
      sha256: d2e9f68c052b2225130977429d30f187aa1981d789c76ad104a32243cfdebfbb
      url: "https://pub.dev"
    source: hosted
    version: "0.3.1"
  language_server_protocol:
    dependency: transitive
    description:
      path: "third_party/pkg/language_server_protocol"
      ref: HEAD
      resolved-ref: "69f76f108679e2d05f809694e33362708ce2aa24"
      url: "https://github.com/dart-lang/sdk.git"
    source: git
    version: "0.0.0"
  leak_tracker:
    dependency: transitive
    description:
      name: leak_tracker
      sha256: "6bb818ecbdffe216e81182c2f0714a2e62b593f4a4f13098713ff1685dfb6ab0"
      url: "https://pub.dev"
    source: hosted
    version: "10.0.9"
  leak_tracker_flutter_testing:
    dependency: transitive
    description:
      name: leak_tracker_flutter_testing
      sha256: f8b613e7e6a13ec79cfdc0e97638fddb3ab848452eff057653abd3edba760573
      url: "https://pub.dev"
    source: hosted
    version: "3.0.9"
  leak_tracker_testing:
    dependency: transitive
    description:
      name: leak_tracker_testing
      sha256: "6ba465d5d76e67ddf503e1161d1f4a6bc42306f9d66ca1e8f079a47290fb06d3"
      url: "https://pub.dev"
    source: hosted
    version: "3.0.1"
  lints:
    dependency: transitive
    description:
      name: lints
      sha256: c35bb79562d980e9a453fc715854e1ed39e24e7d0297a880ef54e17f9874a9d7
      url: "https://pub.dev"
    source: hosted
    version: "5.1.1"
  logger:
    dependency: "direct main"
    description:
      name: logger
      sha256: be4b23575aac7ebf01f225a241eb7f6b5641eeaf43c6a8613510fc2f8cf187d1
      url: "https://pub.dev"
    source: hosted
    version: "2.5.0"
  logging:
    dependency: transitive
    description:
      name: logging
      sha256: c8245ada5f1717ed44271ed1c26b8ce85ca3228fd2ffdb75468ab01979309d61
      url: "https://pub.dev"
    source: hosted
    version: "1.3.0"
  matcher:
    dependency: transitive
    description:
      name: matcher
      sha256: dc58c723c3c24bf8d3e2d3ad3f2f9d7bd9cf43ec6feaa64181775e60190153f2
      url: "https://pub.dev"
    source: hosted
    version: "0.12.17"
  material_color_utilities:
    dependency: transitive
    description:
      name: material_color_utilities
      sha256: f7142bb1154231d7ea5f96bc7bde4bda2a0945d2806bb11670e30b850d56bdec
      url: "https://pub.dev"
    source: hosted
    version: "0.11.1"
  meta:
    dependency: transitive
    description:
      name: meta
      sha256: e3641ec5d63ebf0d9b41bd43201a66e3fc79a65db5f61fc181f04cd27aab950c
      url: "https://pub.dev"
    source: hosted
    version: "1.16.0"
  mime:
    dependency: transitive
    description:
      name: mime
      sha256: "41a20518f0cb1256669420fdba0cd90d21561e560ac240f26ef8322e45bb7ed6"
      url: "https://pub.dev"
    source: hosted
    version: "2.0.0"
  mockito:
    dependency: "direct dev"
    description:
      name: mockito
      sha256: "4546eac99e8967ea91bae633d2ca7698181d008e95fa4627330cf903d573277a"
      url: "https://pub.dev"
    source: hosted
    version: "5.4.6"
  mocktail:
    dependency: transitive
    description:
      name: mocktail
      sha256: "890df3f9688106f25755f26b1c60589a92b3ab91a22b8b224947ad041bf172d8"
      url: "https://pub.dev"
    source: hosted
    version: "1.0.4"
  nested:
    dependency: transitive
    description:
      name: nested
      sha256: "03bac4c528c64c95c722ec99280375a6f2fc708eec17c7b3f07253b626cd2a20"
      url: "https://pub.dev"
    source: hosted
    version: "1.0.0"
  nm:
    dependency: transitive
    description:
      name: nm
      sha256: "2c9aae4127bdc8993206464fcc063611e0e36e72018696cd9631023a31b24254"
      url: "https://pub.dev"
    source: hosted
    version: "0.5.0"
  node_preamble:
    dependency: transitive
    description:
      name: node_preamble
      sha256: "6e7eac89047ab8a8d26cf16127b5ed26de65209847630400f9aefd7cd5c730db"
      url: "https://pub.dev"
    source: hosted
    version: "2.0.2"
  octo_image:
    dependency: transitive
    description:
      name: octo_image
      sha256: "34faa6639a78c7e3cbe79be6f9f96535867e879748ade7d17c9b1ae7536293bd"
      url: "https://pub.dev"
    source: hosted
    version: "2.1.0"
  package_config:
    dependency: transitive
    description:
      name: package_config
      sha256: f096c55ebb7deb7e384101542bfba8c52696c1b56fca2eb62827989ef2353bbc
      url: "https://pub.dev"
    source: hosted
    version: "2.2.0"
  path:
    dependency: transitive
    description:
      name: path
      sha256: "75cca69d1490965be98c73ceaea117e8a04dd21217b37b292c9ddbec0d955bc5"
      url: "https://pub.dev"
    source: hosted
    version: "1.9.1"
  path_parsing:
    dependency: transitive
    description:
      name: path_parsing
      sha256: "883402936929eac138ee0a45da5b0f2c80f89913e6dc3bf77eb65b84b409c6ca"
      url: "https://pub.dev"
    source: hosted
    version: "1.1.0"
  path_provider:
    dependency: "direct main"
    description:
      name: path_provider
      sha256: "50c5dd5b6e1aaf6fb3a78b33f6aa3afca52bf903a8a5298f53101fdaee55bbcd"
      url: "https://pub.dev"
    source: hosted
    version: "2.1.5"
  path_provider_android:
    dependency: transitive
    description:
      name: path_provider_android
      sha256: d0d310befe2c8ab9e7f393288ccbb11b60c019c6b5afc21973eeee4dda2b35e9
      url: "https://pub.dev"
    source: hosted
    version: "2.2.17"
  path_provider_foundation:
    dependency: transitive
    description:
      name: path_provider_foundation
      sha256: "4843174df4d288f5e29185bd6e72a6fbdf5a4a4602717eed565497429f179942"
      url: "https://pub.dev"
    source: hosted
    version: "2.4.1"
  path_provider_linux:
    dependency: transitive
    description:
      name: path_provider_linux
      sha256: f7a1fe3a634fe7734c8d3f2766ad746ae2a2884abe22e241a8b301bf5cac3279
      url: "https://pub.dev"
    source: hosted
    version: "2.2.1"
  path_provider_platform_interface:
    dependency: transitive
    description:
      name: path_provider_platform_interface
      sha256: "88f5779f72ba699763fa3a3b06aa4bf6de76c8e5de842cf6f29e2e06476c2334"
      url: "https://pub.dev"
    source: hosted
    version: "2.1.2"
  path_provider_windows:
    dependency: transitive
    description:
      name: path_provider_windows
      sha256: bd6f00dbd873bfb70d0761682da2b3a2c2fccc2b9e84c495821639601d81afe7
      url: "https://pub.dev"
    source: hosted
    version: "2.3.0"
  petitparser:
    dependency: transitive
    description:
      name: petitparser
      sha256: "07c8f0b1913bcde1ff0d26e57ace2f3012ccbf2b204e070290dad3bb22797646"
      url: "https://pub.dev"
    source: hosted
    version: "6.1.0"
  platform:
    dependency: transitive
    description:
      name: platform
      sha256: "5d6b1b0036a5f331ebc77c850ebc8506cbc1e9416c27e59b439f917a902a4984"
      url: "https://pub.dev"
    source: hosted
    version: "3.1.6"
  plugin_platform_interface:
    dependency: transitive
    description:
      name: plugin_platform_interface
      sha256: "4820fbfdb9478b1ebae27888254d445073732dae3d6ea81f0b7e06d5dedc3f02"
      url: "https://pub.dev"
    source: hosted
    version: "2.1.8"
  pool:
    dependency: transitive
    description:
      name: pool
      sha256: "20fe868b6314b322ea036ba325e6fc0711a22948856475e2c2b6306e8ab39c2a"
      url: "https://pub.dev"
    source: hosted
    version: "1.5.1"
  posix:
    dependency: transitive
    description:
      name: posix
      sha256: f0d7856b6ca1887cfa6d1d394056a296ae33489db914e365e2044fdada449e62
      url: "https://pub.dev"
    source: hosted
    version: "6.0.2"
  postgrest:
    dependency: transitive
    description:
      name: postgrest
      sha256: "10b81a23b1c829ccadf68c626b4d66666453a1474d24c563f313f5ca7851d575"
      url: "https://pub.dev"
    source: hosted
    version: "2.4.2"
  process:
    dependency: transitive
    description:
      name: process
      sha256: "44b4226c0afd4bc3b7c7e67d44c4801abd97103cf0c84609e2654b664ca2798c"
      url: "https://pub.dev"
    source: hosted
    version: "5.0.4"
  provider:
    dependency: transitive
    description:
      name: provider
      sha256: "4abbd070a04e9ddc287673bf5a030c7ca8b685ff70218720abab8b092f53dd84"
      url: "https://pub.dev"
    source: hosted
    version: "6.1.5"
  pub_semver:
    dependency: transitive
    description:
      name: pub_semver
      sha256: "5bfcf68ca79ef689f8990d1160781b4bad40a3bd5e5218ad4076ddb7f4081585"
      url: "https://pub.dev"
    source: hosted
    version: "2.2.0"
  pubspec_parse:
    dependency: transitive
    description:
      name: pubspec_parse
      sha256: "0560ba233314abbed0a48a2956f7f022cce7c3e1e73df540277da7544cad4082"
      url: "https://pub.dev"
    source: hosted
    version: "1.5.0"
  realtime_client:
    dependency: transitive
    description:
      name: realtime_client
      sha256: b6a825a4c80f2281ebfbbcf436a8979ae9993d4a30dbcf011b7d2b82ddde9edd
      url: "https://pub.dev"
    source: hosted
    version: "2.5.1"
  recase:
    dependency: transitive
    description:
      name: recase
      sha256: e4eb4ec2dcdee52dcf99cb4ceabaffc631d7424ee55e56f280bc039737f89213
      url: "https://pub.dev"
    source: hosted
    version: "4.1.0"
  retry:
    dependency: transitive
    description:
      name: retry
      sha256: "822e118d5b3aafed083109c72d5f484c6dc66707885e07c0fbcb8b986bba7efc"
      url: "https://pub.dev"
    source: hosted
    version: "3.1.2"
  rxdart:
    dependency: transitive
    description:
      name: rxdart
      sha256: "5c3004a4a8dbb94bd4bf5412a4def4acdaa12e12f269737a5751369e12d1a962"
      url: "https://pub.dev"
    source: hosted
    version: "0.28.0"
  sanitize_html:
    dependency: transitive
    description:
      name: sanitize_html
      sha256: "12669c4a913688a26555323fb9cec373d8f9fbe091f2d01c40c723b33caa8989"
      url: "https://pub.dev"
    source: hosted
    version: "2.1.0"
  shared_preferences:
    dependency: "direct main"
    description:
      name: shared_preferences
      sha256: "6e8bf70b7fef813df4e9a36f658ac46d107db4b4cfe1048b477d4e453a8159f5"
      url: "https://pub.dev"
    source: hosted
    version: "2.5.3"
  shared_preferences_android:
    dependency: transitive
    description:
      name: shared_preferences_android
      sha256: "20cbd561f743a342c76c151d6ddb93a9ce6005751e7aa458baad3858bfbfb6ac"
      url: "https://pub.dev"
    source: hosted
    version: "2.4.10"
  shared_preferences_foundation:
    dependency: transitive
    description:
      name: shared_preferences_foundation
      sha256: "6a52cfcdaeac77cad8c97b539ff688ccfc458c007b4db12be584fbe5c0e49e03"
      url: "https://pub.dev"
    source: hosted
    version: "2.5.4"
  shared_preferences_linux:
    dependency: transitive
    description:
      name: shared_preferences_linux
      sha256: "580abfd40f415611503cae30adf626e6656dfb2f0cee8f465ece7b6defb40f2f"
      url: "https://pub.dev"
    source: hosted
    version: "2.4.1"
  shared_preferences_platform_interface:
    dependency: transitive
    description:
      name: shared_preferences_platform_interface
      sha256: "57cbf196c486bc2cf1f02b85784932c6094376284b3ad5779d1b1c6c6a816b80"
      url: "https://pub.dev"
    source: hosted
    version: "2.4.1"
  shared_preferences_web:
    dependency: transitive
    description:
      name: shared_preferences_web
      sha256: c49bd060261c9a3f0ff445892695d6212ff603ef3115edbb448509d407600019
      url: "https://pub.dev"
    source: hosted
    version: "2.4.3"
  shared_preferences_windows:
    dependency: transitive
    description:
      name: shared_preferences_windows
      sha256: "94ef0f72b2d71bc3e700e025db3710911bd51a71cefb65cc609dd0d9a982e3c1"
      url: "https://pub.dev"
    source: hosted
    version: "2.4.1"
  shelf:
    dependency: transitive
    description:
      name: shelf
      sha256: e7dd780a7ffb623c57850b33f43309312fc863fb6aa3d276a754bb299839ef12
      url: "https://pub.dev"
    source: hosted
    version: "1.4.2"
  shelf_packages_handler:
    dependency: transitive
    description:
      name: shelf_packages_handler
      sha256: "89f967eca29607c933ba9571d838be31d67f53f6e4ee15147d5dc2934fee1b1e"
      url: "https://pub.dev"
    source: hosted
    version: "3.0.2"
  shelf_static:
    dependency: transitive
    description:
      name: shelf_static
      sha256: c87c3875f91262785dade62d135760c2c69cb217ac759485334c5857ad89f6e3
      url: "https://pub.dev"
    source: hosted
    version: "1.1.3"
  shelf_web_socket:
    dependency: transitive
    description:
      name: shelf_web_socket
      sha256: "3632775c8e90d6c9712f883e633716432a27758216dfb61bd86a8321c0580925"
      url: "https://pub.dev"
    source: hosted
    version: "3.0.0"
  shimmer:
    dependency: "direct main"
    description:
      name: shimmer
      sha256: "5f88c883a22e9f9f299e5ba0e4f7e6054857224976a5d9f839d4ebdc94a14ac9"
      url: "https://pub.dev"
    source: hosted
    version: "3.0.0"
  sky_engine:
    dependency: transitive
    description: flutter
    source: sdk
    version: "0.0.0"
  source_gen:
    dependency: transitive
    description:
      name: source_gen
      sha256: "35c8150ece9e8c8d263337a265153c3329667640850b9304861faea59fc98f6b"
      url: "https://pub.dev"
    source: hosted
    version: "2.0.0"
  source_map_stack_trace:
    dependency: transitive
    description:
      name: source_map_stack_trace
      sha256: c0713a43e323c3302c2abe2a1cc89aa057a387101ebd280371d6a6c9fa68516b
      url: "https://pub.dev"
    source: hosted
    version: "2.1.2"
  source_maps:
    dependency: transitive
    description:
      name: source_maps
      sha256: "190222579a448b03896e0ca6eca5998fa810fda630c1d65e2f78b3f638f54812"
      url: "https://pub.dev"
    source: hosted
    version: "0.10.13"
  source_span:
    dependency: transitive
    description:
      name: source_span
      sha256: "254ee5351d6cb365c859e20ee823c3bb479bf4a293c22d17a9f1bf144ce86f7c"
      url: "https://pub.dev"
    source: hosted
    version: "1.10.1"
  sprintf:
    dependency: transitive
    description:
      name: sprintf
      sha256: "1fc9ffe69d4df602376b52949af107d8f5703b77cda567c4d7d86a0693120f23"
      url: "https://pub.dev"
    source: hosted
    version: "7.0.0"
  sqflite:
    dependency: transitive
    description:
      name: sqflite
      sha256: e2297b1da52f127bc7a3da11439985d9b536f75070f3325e62ada69a5c585d03
      url: "https://pub.dev"
    source: hosted
    version: "2.4.2"
  sqflite_android:
    dependency: transitive
    description:
      name: sqflite_android
      sha256: "2b3070c5fa881839f8b402ee4a39c1b4d561704d4ebbbcfb808a119bc2a1701b"
      url: "https://pub.dev"
    source: hosted
    version: "2.4.1"
  sqflite_common:
    dependency: transitive
    description:
      name: sqflite_common
      sha256: "84731e8bfd8303a3389903e01fb2141b6e59b5973cacbb0929021df08dddbe8b"
      url: "https://pub.dev"
    source: hosted
    version: "2.5.5"
  sqflite_darwin:
    dependency: transitive
    description:
      name: sqflite_darwin
      sha256: "279832e5cde3fe99e8571879498c9211f3ca6391b0d818df4e17d9fff5c6ccb3"
      url: "https://pub.dev"
    source: hosted
    version: "2.4.2"
  sqflite_platform_interface:
    dependency: transitive
    description:
      name: sqflite_platform_interface
      sha256: "8dd4515c7bdcae0a785b0062859336de775e8c65db81ae33dd5445f35be61920"
      url: "https://pub.dev"
    source: hosted
    version: "2.4.0"
  sse:
    dependency: transitive
    description:
      name: sse
      sha256: fcc97470240bb37377f298e2bd816f09fd7216c07928641c0560719f50603643
      url: "https://pub.dev"
    source: hosted
    version: "4.1.8"
  stack_trace:
    dependency: transitive
    description:
      name: stack_trace
      sha256: "8b27215b45d22309b5cddda1aa2b19bdfec9df0e765f2de506401c071d38d1b1"
      url: "https://pub.dev"
    source: hosted
    version: "1.12.1"
  storage_client:
    dependency: transitive
    description:
      name: storage_client
      sha256: "09bac4d75eea58e8113ca928e6655a09cc8059e6d1b472ee801f01fde815bcfc"
      url: "https://pub.dev"
    source: hosted
    version: "2.4.0"
  stream_channel:
    dependency: transitive
    description:
      name: stream_channel
      sha256: "969e04c80b8bcdf826f8f16579c7b14d780458bd97f56d107d3950fdbeef059d"
      url: "https://pub.dev"
    source: hosted
    version: "2.1.4"
  stream_transform:
    dependency: transitive
    description:
      name: stream_transform
      sha256: ad47125e588cfd37a9a7f86c7d6356dde8dfe89d071d293f80ca9e9273a33871
      url: "https://pub.dev"
    source: hosted
    version: "2.1.1"
  string_scanner:
    dependency: transitive
    description:
      name: string_scanner
      sha256: "921cd31725b72fe181906c6a94d987c78e3b98c2e205b397ea399d4054872b43"
      url: "https://pub.dev"
    source: hosted
    version: "1.4.1"
  supabase:
    dependency: transitive
    description:
      name: supabase
      sha256: "56c3493114caac8ef0dc3cac5fa24a9edefeb8c22d45794814c0fe3d2feb1a98"
      url: "https://pub.dev"
    source: hosted
    version: "2.8.0"
  supabase_flutter:
    dependency: "direct main"
    description:
      name: supabase_flutter
      sha256: "66b8d0a7a31f45955b11ad7b65347abc61b31e10f8bdfa4428501b81f5b30fa5"
      url: "https://pub.dev"
    source: hosted
    version: "2.9.1"
  synchronized:
    dependency: transitive
    description:
      name: synchronized
      sha256: "0669c70faae6270521ee4f05bffd2919892d42d1276e6c495be80174b6bc0ef6"
      url: "https://pub.dev"
    source: hosted
    version: "3.3.1"
  term_glyph:
    dependency: transitive
    description:
      name: term_glyph
      sha256: "7f554798625ea768a7518313e58f83891c7f5024f88e46e7182a4558850a4b8e"
      url: "https://pub.dev"
    source: hosted
    version: "1.2.2"
  test:
    dependency: transitive
    description:
      name: test
      sha256: "301b213cd241ca982e9ba50266bd3f5bd1ea33f1455554c5abb85d1be0e2d87e"
      url: "https://pub.dev"
    source: hosted
    version: "1.25.15"
  test_api:
    dependency: transitive
    description:
      name: test_api
      sha256: fb31f383e2ee25fbbfe06b40fe21e1e458d14080e3c67e7ba0acfde4df4e0bbd
      url: "https://pub.dev"
    source: hosted
    version: "0.7.4"
  test_core:
    dependency: transitive
    description:
      name: test_core
      sha256: "84d17c3486c8dfdbe5e12a50c8ae176d15e2a771b96909a9442b40173649ccaa"
      url: "https://pub.dev"
    source: hosted
    version: "0.6.8"
  timing:
    dependency: transitive
    description:
      name: timing
      sha256: "62ee18aca144e4a9f29d212f5a4c6a053be252b895ab14b5821996cff4ed90fe"
      url: "https://pub.dev"
    source: hosted
    version: "1.0.2"
  typed_data:
    dependency: transitive
    description:
      name: typed_data
      sha256: f9049c039ebfeb4cf7a7104a675823cd72dba8297f264b6637062516699fa006
      url: "https://pub.dev"
    source: hosted
    version: "1.4.0"
  unified_analytics:
    dependency: transitive
    description:
      name: unified_analytics
      sha256: c8abdcad84b55b78f860358aae90077b8f54f98169a75e16d97796a1b3c95590
      url: "https://pub.dev"
    source: hosted
    version: "8.0.1"
  universal_io:
    dependency: transitive
    description:
      name: universal_io
      sha256: "1722b2dcc462b4b2f3ee7d188dad008b6eb4c40bbd03a3de451d82c78bba9aad"
      url: "https://pub.dev"
    source: hosted
    version: "2.2.2"
  url_launcher:
    dependency: "direct main"
    description:
      name: url_launcher
      sha256: "9d06212b1362abc2f0f0d78e6f09f726608c74e3b9462e8368bb03314aa8d603"
      url: "https://pub.dev"
    source: hosted
    version: "6.3.1"
  url_launcher_android:
    dependency: transitive
    description:
      name: url_launcher_android
      sha256: "8582d7f6fe14d2652b4c45c9b6c14c0b678c2af2d083a11b604caeba51930d79"
      url: "https://pub.dev"
    source: hosted
    version: "6.3.16"
  url_launcher_ios:
    dependency: transitive
    description:
      name: url_launcher_ios
      sha256: "7f2022359d4c099eea7df3fdf739f7d3d3b9faf3166fb1dd390775176e0b76cb"
      url: "https://pub.dev"
    source: hosted
    version: "6.3.3"
  url_launcher_linux:
    dependency: transitive
    description:
      name: url_launcher_linux
      sha256: "4e9ba368772369e3e08f231d2301b4ef72b9ff87c31192ef471b380ef29a4935"
      url: "https://pub.dev"
    source: hosted
    version: "3.2.1"
  url_launcher_macos:
    dependency: transitive
    description:
      name: url_launcher_macos
      sha256: "17ba2000b847f334f16626a574c702b196723af2a289e7a93ffcb79acff855c2"
      url: "https://pub.dev"
    source: hosted
    version: "3.2.2"
  url_launcher_platform_interface:
    dependency: transitive
    description:
      name: url_launcher_platform_interface
      sha256: "552f8a1e663569be95a8190206a38187b531910283c3e982193e4f2733f01029"
      url: "https://pub.dev"
    source: hosted
    version: "2.3.2"
  url_launcher_web:
    dependency: transitive
    description:
      name: url_launcher_web
      sha256: "4bd2b7b4dc4d4d0b94e5babfffbca8eac1a126c7f3d6ecbc1a11013faa3abba2"
      url: "https://pub.dev"
    source: hosted
    version: "2.4.1"
  url_launcher_windows:
    dependency: transitive
    description:
      name: url_launcher_windows
      sha256: "3284b6d2ac454cf34f114e1d3319866fdd1e19cdc329999057e44ffe936cfa77"
      url: "https://pub.dev"
    source: hosted
    version: "3.1.4"
  uuid:
    dependency: "direct main"
    description:
      name: uuid
      sha256: a5be9ef6618a7ac1e964353ef476418026db906c4facdedaa299b7a2e71690ff
      url: "https://pub.dev"
    source: hosted
    version: "4.5.1"
  vector_graphics:
    dependency: transitive
    description:
      name: vector_graphics
      sha256: a4f059dc26fc8295b5921376600a194c4ec7d55e72f2fe4c7d2831e103d461e6
      url: "https://pub.dev"
    source: hosted
    version: "1.1.19"
  vector_graphics_codec:
    dependency: transitive
    description:
      name: vector_graphics_codec
      sha256: "99fd9fbd34d9f9a32efd7b6a6aae14125d8237b10403b422a6a6dfeac2806146"
      url: "https://pub.dev"
    source: hosted
    version: "1.1.13"
  vector_graphics_compiler:
    dependency: transitive
    description:
      name: vector_graphics_compiler
      sha256: "557a315b7d2a6dbb0aaaff84d857967ce6bdc96a63dc6ee2a57ce5a6ee5d3331"
      url: "https://pub.dev"
    source: hosted
    version: "1.1.17"
  vector_math:
    dependency: transitive
    description:
      name: vector_math
      sha256: "80b3257d1492ce4d091729e3a67a60407d227c27241d6927be0130c98e741803"
      url: "https://pub.dev"
    source: hosted
    version: "2.1.4"
  vm_service:
    dependency: transitive
    description:
      name: vm_service
      sha256: ddfa8d30d89985b96407efce8acbdd124701f96741f2d981ca860662f1c0dc02
      url: "https://pub.dev"
    source: hosted
    version: "15.0.0"
  watcher:
    dependency: transitive
    description:
      name: watcher
      sha256: "0b7fd4a0bbc4b92641dbf20adfd7e3fd1398fe17102d94b674234563e110088a"
      url: "https://pub.dev"
    source: hosted
    version: "1.1.2"
  web:
    dependency: transitive
    description:
      name: web
      sha256: "868d88a33d8a87b18ffc05f9f030ba328ffefba92d6c127917a2ba740f9cfe4a"
      url: "https://pub.dev"
    source: hosted
    version: "1.1.1"
  web_socket:
    dependency: transitive
    description:
      name: web_socket
      sha256: "34d64019aa8e36bf9842ac014bb5d2f5586ca73df5e4d9bf5c936975cae6982c"
      url: "https://pub.dev"
    source: hosted
    version: "1.0.1"
  web_socket_channel:
    dependency: transitive
    description:
      name: web_socket_channel
      sha256: d645757fb0f4773d602444000a8131ff5d48c9e47adfe9772652dd1a4f2d45c8
      url: "https://pub.dev"
    source: hosted
    version: "3.0.3"
  webkit_inspection_protocol:
    dependency: transitive
    description:
      name: webkit_inspection_protocol
      sha256: "87d3f2333bb240704cd3f1c6b5b7acd8a10e7f0bc28c28dcf14e782014f4a572"
      url: "https://pub.dev"
    source: hosted
    version: "1.2.1"
  win32:
    dependency: transitive
    description:
      name: win32
      sha256: "66814138c3562338d05613a6e368ed8cfb237ad6d64a9e9334be3f309acfca03"
      url: "https://pub.dev"
    source: hosted
    version: "5.14.0"
  xdg_directories:
    dependency: transitive
    description:
      name: xdg_directories
      sha256: "7a3f37b05d989967cdddcbb571f1ea834867ae2faa29725fd085180e0883aa15"
      url: "https://pub.dev"
    source: hosted
    version: "1.1.0"
  xml:
    dependency: transitive
    description:
      name: xml
      sha256: b015a8ad1c488f66851d762d3090a21c600e479dc75e68328c52774040cf9226
      url: "https://pub.dev"
    source: hosted
    version: "6.5.0"
  yaml:
    dependency: transitive
    description:
      name: yaml
      sha256: b9da305ac7c39faa3f030eccd175340f968459dae4af175130b3fc47e40d76ce
      url: "https://pub.dev"
    source: hosted
    version: "3.1.3"
  yaml_edit:
    dependency: transitive
    description:
      name: yaml_edit
      sha256: fb38626579fb345ad00e674e2af3a5c9b0cc4b9bfb8fd7f7ff322c7c9e62aef5
      url: "https://pub.dev"
    source: hosted
    version: "2.2.2"
  yet_another_json_isolate:
    dependency: transitive
    description:
      name: yet_another_json_isolate
      sha256: fe45897501fa156ccefbfb9359c9462ce5dec092f05e8a56109db30be864f01e
      url: "https://pub.dev"
    source: hosted
    version: "2.1.0"
sdks:
  dart: ">=3.8.1 <4.0.0"
  flutter: ">=3.27.0"

</file>
<file path="pubspec.yaml">
name: foodster
description: "Smart nutrition and grocery management app"
publish_to: "none"

version: 1.0.0+1

environment:
  sdk: ^3.8.1

dependencies:
  flutter:
    sdk: flutter
  flutter_localizations:
    sdk: flutter

  # UI
  cupertino_icons: ^1.0.8
  flutter_svg: ^2.0.9
  cached_network_image: ^3.3.1
  fl_chart: ^0.66.0
  shimmer: ^3.0.0
  google_fonts: ^6.1.0

  # State Management
  flutter_bloc: ^8.1.3
  equatable: ^2.0.5

  # DI
  get_it: ^7.6.4
  injectable: ^2.3.2

  # Backend & API
  supabase_flutter: ^2.2.0
  dio: ^5.4.1
  http: ^1.2.1
  connectivity_plus: ^5.0.2

  # Local Storage
  shared_preferences: ^2.2.2
  hive_flutter: ^1.1.0
  flutter_secure_storage: ^9.0.0

  # Utils
  intl: ^0.20.2
  logger: ^2.0.2+1
  path_provider: ^2.1.2
  url_launcher: ^6.2.4
  flutter_dotenv: ^5.1.0
  google_maps_flutter: ^2.5.3
  uuid: ^4.2.2
  dartz: ^0.10.1

dev_dependencies:
  flutter_test:
    sdk: flutter
  flutter_lints: ^5.0.0
  build_runner: ^2.4.7
  injectable_generator: ^2.4.1
  bloc_test: ^9.1.5
  mockito: ^5.4.4
  flutter_launcher_icons: ^0.13.1
  flutter_native_splash: ^2.3.11
  dart_mcp_server:
    git:
      url: https://github.com/dart-lang/ai.git
      path: pkgs/dart_mcp_server
      ref: main

# For information on the generic Dart part of this file, see the
# following page: https://dart.dev/tools/pub/pubspec

# The following section is specific to Flutter packages.
flutter:
  # The following line ensures that the Material Icons font is
  # included with your application, so that you can use the icons in
  # the material Icons class.
  uses-material-design: true

  # Assets
  assets:
    - assets/images/
    - assets/icons/
    - .env

  # We'll use Google Fonts instead of custom fonts
  # until we have the actual font files

</file>
<file path="README.md">
# Foodster

**Foodster** is a smart nutrition and grocery planning app that helps users:

- Create healthy, personalized meal plans
- Automatically generate grocery lists
- Compare store prices and stay within budget
- Get nutrition info and cooking tips
- Find affordable, healthier alternatives

## 🚀 Tech Stack

| Layer    | Technology                                          |
| -------- | --------------------------------------------------- |
| Frontend | Flutter (Mobile, Web, Desktop - planned)            |
| Backend  | Supabase Edge Functions (Node.js / TypeScript)      |
| Database | Supabase PostgreSQL                                 |
| Realtime | Supabase Realtime                                   |
| Auth     | Supabase Auth                                       |
| APIs     | Edamam, Spoonacular, FatSecret, Kroger, Google Maps |

## 📱 Platforms

- ✅ Mobile (Android/iOS)
- ✅ Web (via Flutter Web)
- 🖥️ Desktop (Planned via Flutter Desktop)

## 🔧 Features

- 🧭 Guided Onboarding:
  - Dietary preferences
  - Household details
  - Budget & goals
  - Region support
- 🧬 Personalized Meal Plan Generator
- 🛒 Grocery List Builder
- 💰 Budget Tracker + Reverse Budget Mode
- 🏪 Store Price Comparison + Distance Lookup
- 🧠 AI-Suggested Healthier/Cheaper Swaps
- 📦 Supabase-native: Auth, DB, Storage, Realtime
- 🔔 Notifications + Offline Support

## 📁 Project Structure

```
/lib                  → Flutter app (screens, models, services)
/functions            → Supabase Edge Functions (Node.js)
/docs                 → Documentation files (requirements, specs)
/assets               → Icons, images
.env.local            → API keys and secrets (not committed)
```

## 🧠 Dev Guidelines

- Use TypeScript for all Edge Functions
- Use Supabase CLI for local testing & deployment
- Cache expensive API results (nutrition, store prices)
- Validate all Edge Function inputs using `zod`
- Protect all tables using RLS

## 📄 Docs

- [PROJECT_REQUIREMENTS.md](./docs/PROJECT_REQUIREMENTS.md)
- [COPILOT_INSTRUCTIONS_SERVERLESS.md](./docs/COPILOT_INSTRUCTIONS.md)
- [DESIGN_SPECS.md](./docs/DESIGN_SPECS.md)

---

Built with ❤️ using Supabase, Flutter, and open food/nutrition APIs.

</file>
