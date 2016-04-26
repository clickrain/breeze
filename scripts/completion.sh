# BASH ~4.x, ZSH
# source <(breeze _completion --generate-hook)

# BASH ~3.x, ZSH
# breeze _completion --generate-hook | source /dev/stdin

# BASH (any version)
# eval $(breeze _completion --generate-hook)

# For OSX, check out:
#   https://github.com/bobthecow/git-flow-completion/wiki/Install-Bash-git-completion
# for how to add bash completion

eval $(breeze _completion --generate-hook --program breeze)
