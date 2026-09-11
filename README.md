# Sarvam AI Provider for Moodle

A Moodle AI provider plugin that integrates [Sarvam AI](https://www.sarvam.ai/) into Moodle's AI subsystem, enabling text generation, text summarisation, and text explanation

## Features

- Text generation
- Text summarisation
- Text explaination

## Requirements

- Moodle 5.2 or later
- PHP 8.3 or later
- A valid [Sarvam API key](https://indus.sarvam.ai/key-management)

## Configuration

### Provider settings

| Setting         | Description                                                                |
| --------------- | -------------------------------------------------------------------------- |
| API key         | Your Sarvam API key from [https://indus.sarvam.ai/key-management](https://indus.sarvam.ai/key-management) |
| API version     | Pre-filled with the correct default |

### Action settings

Each action (generate text, summarise text, explain text) can be configured independently with:

| Setting            | Description                                                   |
| ------------------ | ------------------------------------------------------------- |
| Model              | The Sarvam model to use                 |
| Endpoint           | The Sarvam API endpoint (pre-filled with the correct default) |
| System instruction | Custom system prompt (text actions only)                      |
| Extra parameters   | Additional model parameters        |

## Architecture

### Text actions

Text generation, summarisation, and explanation all use the Sarvam chat completion API (`/v1/chat/completions`).


## License

This plugin is licensed under the [GNU GPL v3 or later](http://www.gnu.org/copyleft/gpl.html).

## Installation

Install by downloading a zip. Log in as an administrator and visit **Site administration → Plugins → Install plugin ** and upload the zip.

### Download the zip

1. Visit the Moodle plugins directory and download the version that matches your Moodle release:
   - <https://moodle.org/plugins/aiprovider_sarvam>
2. Extract the zip.
3. Copy the extracted `sarvam` folder into your Moodle `ai/provider/` directory so the path becomes:
   - `moodle/ai/provider/sarvam`
4. Log in as an administrator and visit **Site administration → Notifications**.
